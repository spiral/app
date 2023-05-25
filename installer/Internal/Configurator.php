<?php

declare(strict_types=1);

namespace Installer\Internal;

use App\Application\Bootloader\AppBootloader;
use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Bootloader\RoutesBootloader;
use App\Application\Kernel;
use Composer\Composer;
use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Installer\Internal\Application\AbstractApplication;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Configurator\InstallationInstructionRenderer;
use Installer\Internal\Configurator\ReadmeGenerator;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Configurator\RoadRunnerConfigGenerator;
use Installer\Internal\Console\IO;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Events\DeleteEvent;
use Installer\Internal\Generator;
use Installer\Internal\Generator\Bootloader\RoutesBootloaderConfigurator;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Installer\AbstractInstaller;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorage;
use Installer\Internal\Installer\ComposerStorageInterface;
use Installer\Internal\Process\ProcessExecutor;
use Seld\JsonLint\ParsingException;
use Spiral\Core\Container;
use Spiral\Files\Files;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;

final class Configurator extends AbstractInstaller
{
    /**
     * @throws ParsingException
     * @throws \Exception
     */
    public static function configure(Event $event): void
    {
        $conf = new self(
            new IO($event->getIO()),
            Factory::getComposerFile(),
            $event->getComposer(),
        );

        $conf->run();
    }

    private readonly ApplicationInterface $application;
    private readonly Container $container;
    private Generator\Context $context;
    private readonly ComposerFile $composer;
    private readonly FilesInterface $files;
    private ProcessExecutor $processExecutor;

    public function __construct(
        IOInterface $io,
        string $composerFilePath,
        Composer $composer,
        private readonly ClassMetadataRepositoryInterface $classMetadata = new ReflectionClassMetadataRepository(),
        ?string $projectRoot = null,
        ?FilesInterface $files = null,
        ?ComposerStorageInterface $composerStorage = null,
        private readonly ?EventStorage $eventStorage = null,
    ) {
        parent::__construct($io, $composerFilePath, $projectRoot);

        $this->files = $files ?? new Files();
        $this->container = new Container();
        $this->composer = new ComposerFile(
            $composerStorage ?? new ComposerStorage(new JsonFile($this->composerFile), $eventStorage),
            $composer->getPackage(),
            $this->config,
        );

        $this->application = $this->getApplicationType();
        $this->context = $this->buildContext();
        $this->processExecutor = new ProcessExecutor();
    }

    public function run(): void
    {
        $this->runGenerators();
        $this->createRoadRunnerConfig();
        $this->runCommands();
        $this->showInstructions();

        $this->updateReadme();

        // We don't need MIT license file in the application, that's why we remove it.
        $this->removeLicense();
        $this->removeInstaller();

        // Create .env file
        $this->context->envConfigurator->persist();
        unset($this->context);
    }

    private function buildContext(): Generator\Context
    {
        $writer = new Writer($this->files);

        return new Generator\Context(
            application: $this->application,
            kernel: new Generator\Kernel\Configurator(
                writer: $writer,
                class: $this->classMetadata->getMetaData(Kernel::class),
                eventStorage: $this->eventStorage,
            ),
            exceptionHandlerBootloader: new Generator\Bootloader\ExceptionHandlerBootloaderConfigurator(
                writer: $writer,
                class: $this->classMetadata->getMetaData(ExceptionHandlerBootloader::class),
                eventStorage: $this->eventStorage,
            ),
            routesBootloader: new RoutesBootloaderConfigurator(
                writer: $writer,
                class: $this->classMetadata->getMetaData(RoutesBootloader::class),
                eventStorage: $this->eventStorage,
            ),
            domainInterceptors: new Generator\Bootloader\DomainInterceptorsConfigurator(
                writer: $writer,
                class: $this->classMetadata->getMetaData(AppBootloader::class),
            ),
            envConfigurator: $this->buildEnvConfigurator(),
            applicationRoot: $this->projectRoot,
            resource: new ResourceQueue(
                directoriesMap: $this->config->getDirectories()
            )
        );
    }

    private function buildEnvConfigurator(): Generator\Env\Generator
    {
        $generator = new Generator\Env\Generator(
            projectRoot: $this->projectRoot,
            files: $this->files,
            eventStorage: $this->eventStorage,
        );

        foreach ($this->config->getDefaultEnv() as $group) {
            $generator->addGroup(
                $group->values,
                $group->comment,
                $group->priority,
            );
        }

        return $generator;
    }

    private function getApplicationType(): ApplicationInterface
    {
        if ($this->composer->getApplicationType() === null) {
            throw new \InvalidArgumentException('Application type is not defined!');
        }

        $application = $this->config->getApplication($this->composer->getApplicationType());

        if ($application instanceof AbstractApplication) {
            $application->setInstalled(
                $this->composer->getInstalledPackages(),
                $this->composer->getInstalledOptions(),
            );
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
            $this->eventStorage?->addEvent($object);
            $this->eventStorage?->addEvent($generator);

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
                $object instanceof HasResourcesInterface => $object->getResourcesPath(),
                default => null,
            };

            if ($sourceRoot !== null) {
                $this->context->resource->setSourceRoot($sourceRoot);
            }

            $this->io->write('Running generator ' . $generator::class);

            $generator->process($this->context);

            $this->context->resource->setSourceRoot('');
        }

        foreach ($this->context->resource as $task) {
            $this->eventStorage?->addEvent($task);
            foreach (
                $this->resource->copy(
                    $task->getFullSource(),
                    $task->getFullDestination()
                ) as $copyEvent
            ) {
                $this->eventStorage?->addEvent($copyEvent);
                $this->io->comment(\sprintf('%s copied.', (string)$copyEvent));
            }
        }
    }

    private function runCommands(): void
    {
        foreach ($this->application->getCommands() as $command) {
            $process = $this->processExecutor->execute($command);

            $this->eventStorage?->addEvent($process);

            foreach ($process as $output) {
                $this->eventStorage?->addEvent($output);
                $output->send($this->io);
            }
        }
    }

    private function createRoadRunnerConfig(): void
    {
        $generator = new RoadRunnerConfigGenerator($this->processExecutor);

        $process = $generator->generate($this->application->getRoadRunnerPlugins());
        $this->eventStorage?->addEvent($process);

        foreach ($process as $output) {
            $this->eventStorage?->addEvent($output);
            $output->send($this->io);
        }
    }

    private function updateReadme(): void
    {
        (new ReadmeGenerator(
            filePath: $path = $this->projectRoot . '/README.md',
            application: $this->application,
            files: $this->files,
        ))->generate($this->eventStorage);
    }

    private function showInstructions(): void
    {
        $renderer = new InstallationInstructionRenderer($this->application);

        foreach ($renderer->render() as $output) {
            $this->eventStorage?->addEvent($output);
            $output->send($this->io);
        }
    }

    private function removeLicense(): void
    {
        $file = $this->projectRoot . 'LICENSE';

        $this->eventStorage?->addEvent(
            new DeleteEvent($file)
        );

        $this->files->delete($file);
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
            $this->eventStorage?->addEvent($output);
            $output->send($this->io);
        }

        if ($this->files->isDirectory($this->projectRoot . 'installer')) {
            $installerPath = $this->projectRoot . 'installer';

            $this->eventStorage?->addEvent(
                new DeleteEvent($installerPath)
            );
            $this->files->deleteDirectory($installerPath);
        }

        if ($this->files->isFile($this->projectRoot . 'cleanup.sh')) {
            $cleanupPath = $this->projectRoot . 'cleanup.sh';

            $this->eventStorage?->addEvent(
                new DeleteEvent($cleanupPath)
            );
            $this->files->delete($cleanupPath);
        }

        $this->io->success('Installer removed.');
    }
}
