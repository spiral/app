<?php

declare(strict_types=1);

namespace Installer\Internal;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Kernel;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Configurator\BashCommandExecutor;
use Installer\Internal\Configurator\InstallationInstructionRenderer;
use Installer\Internal\Configurator\ReadmeGenerator;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Configurator\RoadRunnerConfigGenerator;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\EnvConfigurator;
use Installer\Internal\Generator\ExceptionHandlerBootloaderConfigurator;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Generator\KernelConfigurator;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ComposerFile;
use Seld\JsonLint\ParsingException;
use Spiral\Core\Container;
use Spiral\Files\Files;
use Symfony\Component\Process\Process;

final class Configurator extends AbstractInstaller
{
    private readonly ApplicationInterface $application;
    private readonly Container $container;
    private readonly Context $context;
    private readonly ComposerFile $composer;
    private readonly FilesInterface $files;

    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null
    ) {
        parent::__construct($io, $projectRoot);

        $this->files = new Files();
        $this->container = new Container();
        $this->composer = new ComposerFile(
            new JsonFile($this->composerFile),
            $composer->getPackage()
        );

        $this->application = $this->getApplicationType();
        $this->context = $this->buildContext();
    }

    private function buildContext(): Context
    {
        return new Context(
            application: $this->application,
            kernel: new KernelConfigurator(Kernel::class),
            exceptionHandlerBootloader: new ExceptionHandlerBootloaderConfigurator(ExceptionHandlerBootloader::class),
            envConfigurator: $this->buildEnvConfigurator(),
            applicationRoot: $this->projectRoot,
            resource: new ResourceQueue($this->projectRoot)
        );
    }

    private function buildEnvConfigurator(): EnvConfigurator
    {
        return (new EnvConfigurator(
            projectRoot: $this->projectRoot,
            resource: $this->resource,
            files: $this->files,
        ))->addGroup(
            values: ['APP_ENV' => 'local'],
            comment: 'Environment (prod or local)',
            priority: 1
        )->addGroup(
            values: ['DEBUG' => true],
            comment: 'Debug mode set to TRUE disables view caching and enables higher verbosity',
            priority: 2
        )->addGroup(
            values: ['VERBOSITY_LEVEL' => 'verbose # basic, verbose, debug'],
            comment: 'Verbosity level',
            priority: 3
        )->addGroup(
            values: ['ENCRYPTER_KEY' => '{encrypt-key}'],
            comment: 'Set to an application specific value, used to encrypt/decrypt cookies etc',
            priority: 4
        )->addGroup(
            values: [
                'MONOLOG_DEFAULT_CHANNEL' => 'default',
                'MONOLOG_DEFAULT_LEVEL' => 'DEBUG # DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY',
            ],
            comment: 'Monolog',
            priority: 5
        )->addGroup(
            values: [
                'TELEMETRY_DRIVER' => 'null',
            ],
            comment: 'Telemetry',
            priority: 9
        );
    }

    private function getApplicationType(): ApplicationInterface
    {
        if ($this->composer->getApplicationType() === null) {
            throw new \InvalidArgumentException('Application type is not defined!');
        }

        $application = $this->config[$this->composer->getApplicationType()] ?? null;

        if (!$application instanceof ApplicationInterface) {
            throw new \InvalidArgumentException('Invalid application type!');
        }

        if ($application instanceof AbstractApplication) {
            $application->setInstalled($this->composer->getInstalledPackages());
        }

        return $application;
    }

    /**
     * @throws ParsingException
     * @throws \Exception
     */
    public static function configure(Event $event): void
    {
        $conf = new self($event->getIO(), $event->getComposer());

        $conf->runGenerators();
        $conf->createRoadRunnerConfig();
        $conf->runCommands();
        $conf->showInstructions();

        $conf->updateReadme();

        // We don't need MIT license file in the application, that's why we remove it.
        $conf->removeLicense();
        $conf->removeInstaller();
    }

    /**
     * Run application generators.
     * It will run at first all the generators for packages that chosen by user, then for application default packages
     * and then for application itself.
     */
    private function runGenerators(): void
    {
        foreach ($this->application->getGenerators() as $object => $generator) {
            if (!$generator instanceof GeneratorInterface) {
                try {
                    $generator = $this->container->get($generator);
                } catch (\Throwable $e) {
                    $this->io->error(
                        \sprintf('Unable to create generator %s. Reason [%s]', $generator::class, $e->getMessage())
                    );
                }
            }

            $sourceRoot = match (true) {
                $object instanceof ApplicationInterface => $object->getResourcesPath(),
                $object instanceof Package => $object->getResourcesPath(),
                default => null,
            };

            if ($sourceRoot !== null) {
                $this->context->resource->setSourceRoot($sourceRoot);
            }

            $generator->process($this->context);
            $this->context->resource->setSourceRoot($this->projectRoot);
        }

        foreach ($this->context->resource as $source => $destination) {
            $this->io->write(\sprintf('Copying %s => %s ....', $source, $destination));

            foreach ($this->resource->copy($source, $destination) as $sourceFile => $destinationFile) {
                $this->io->comment(\sprintf('%s => %s copied.', $sourceFile, $destinationFile));
            }
        }
    }

    private function runCommands(): void
    {
        $executor = new BashCommandExecutor;

        foreach ($executor->execute($this->application->getCommands()) as $type => $output) {
            match ($type) {
                Process::ERR => $this->io->error($output),
                Process::OUT => $this->io->write($output),
            };
        }
    }

    private function createRoadRunnerConfig(): void
    {
        $generator = new RoadRunnerConfigGenerator;

        foreach ($generator->generate($this->application->getRoadRunnerPlugins()) as $type => $output) {
            match ($type) {
                Process::ERR => $this->io->error($output),
                Process::OUT => $this->io->write($output),
            };
        }
    }

    private function updateReadme(): void
    {
        (new ReadmeGenerator(
            filePath: $this->projectRoot . '/README.md',
            application: $this->application,
            files: $this->files,
        ))->generate();
    }

    private function showInstructions(): void
    {
        $renderer = new InstallationInstructionRenderer($this->application);

        foreach ($renderer->render() as $type => $message) {
            $this->io->{$type}($message);
        }
    }

    private function removeLicense(): void
    {
        $this->files->delete($this->projectRoot . 'LICENSE');
    }

    /**
     * @throws \Exception
     */
    private function removeInstaller(): void
    {
        $result = $this->composer->removeInstaller(
            $this->application->getAutoload(),
            $this->application->getAutoloadDev()
        );

        foreach ($result as $message) {
            $this->io->success($message);
        }

        $this->io->success('Removing Installer files ...');
        $this->files->deleteDirectory($this->projectRoot . 'installer');
        $this->files->delete($this->projectRoot . 'cleanup.sh');
    }
}
