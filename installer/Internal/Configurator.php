<?php

declare(strict_types=1);

namespace Installer\Internal;

use App\Application\Kernel;
use Composer\Composer;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Configurator\InstallationInstructionRenderer;
use Installer\Internal\Configurator\ReadmeGenerator;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Configurator\RoadRunnerConfigGenerator;
use Installer\Internal\Console\IO;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Generator;
use Installer\Internal\Generator\Bootloader\RoutesBootloaderConfigurator;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorage;
use Seld\JsonLint\ParsingException;
use Spiral\Core\Container;
use Spiral\Files\Files;
use Spiral\Reactor\Writer;

final class Configurator extends AbstractInstaller
{
    /**
     * @throws ParsingException
     * @throws \Exception
     */
    public static function configure(Event $event): void
    {
        $conf = new self(new IO($event->getIO()), $event->getComposer());

        $conf->runGenerators();
        $conf->createRoadRunnerConfig();
        $conf->runCommands();
        $conf->showInstructions();

        $conf->updateReadme();

        // We don't need MIT license file in the application, that's why we remove it.
        $conf->removeLicense();
        $conf->removeInstaller();

        // Create .env file
        $conf->context->envConfigurator->persist();
    }

    private readonly ApplicationInterface $application;
    private readonly Container $container;
    private readonly Generator\Context $context;
    private readonly ComposerFile $composer;
    private readonly FilesInterface $files;
    private ProcessExecutor $processExecutor;

    public function __construct(
        IOInterface $io,
        Composer $composer,
        ?string $projectRoot = null
    ) {
        parent::__construct($io, $projectRoot);

        $this->files = new Files();
        $this->container = new Container();
        $this->composer = new ComposerFile(
            new ComposerStorage(new JsonFile($this->composerFile)),
            $composer->getPackage()
        );

        $this->application = $this->getApplicationType();
        $this->context = $this->buildContext();
        $this->processExecutor = new ProcessExecutor();
    }

    private function buildContext(): Generator\Context
    {
        $writer = new Writer($this->files);

        return new Generator\Context(
            application: $this->application,
            kernel: new Generator\Kernel\Configurator(
                kernelClass: Kernel::class,
                writer: $writer
            ),
            exceptionHandlerBootloader: new Generator\Bootloader\ExceptionHandlerBootloaderConfigurator(
                writer: $writer
            ),
            routesBootloader: new RoutesBootloaderConfigurator(
                writer: $writer
            ),
            domainInterceptors: new Generator\Bootloader\DomainInterceptorsConfigurator(
                writer: $writer
            ),
            envConfigurator: $this->buildEnvConfigurator(),
            applicationRoot: $this->projectRoot,
            resource: new ResourceQueue($this->projectRoot)
        );
    }

    private function buildEnvConfigurator(): Generator\Env\Generator
    {
        return (new Generator\Env\Generator(
            projectRoot: $this->projectRoot,
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

        foreach ($this->context->resource as $task) {
            $this->io->write(\sprintf('Copying %s ....', (string)$task));

            foreach (
                $this->resource->copy(
                    $task->getFullSource(),
                    $task->getFullDestination()
                ) as $copyTask
            ) {
                $this->io->comment(\sprintf('%s copied.', (string)$copyTask));
            }
        }
    }

    private function runCommands(): void
    {
        foreach ($this->application->getCommands() as $command) {
            foreach ($this->processExecutor->execute($command) as $output) {
                $output->send($this->io);
            }
        }
    }

    private function createRoadRunnerConfig(): void
    {
        $generator = new RoadRunnerConfigGenerator($this->processExecutor);

        foreach ($generator->generate($this->application->getRoadRunnerPlugins()) as $output) {
            $output->send($this->io);
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

        foreach ($renderer->render() as $output) {
            $output->send($this->io);
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

        foreach ($result as $output) {
            $output->send($this->io);
        }

        $this->files->deleteDirectory($this->projectRoot . 'installer');
        $this->files->delete($this->projectRoot . 'cleanup.sh');

        $this->io->success('Installer removed.');
    }
}
