<?php

declare(strict_types=1);

namespace Installer\Internal;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Kernel;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Installer\Internal\Configurator\BashCommandExecutor;
use Installer\Internal\Configurator\InstallationInstructionRenderer;
use Installer\Internal\Configurator\ReadmeGenerator;
use Installer\Internal\Configurator\RoadRunnerConfigGenerator;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\EnvConfigurator;
use Installer\Internal\Generator\ExceptionHandlerBootloaderConfigurator;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Generator\KernelConfigurator;
use Installer\Internal\Installer\AbstractInstaller;
use Spiral\Core\Container;

final class Configurator extends AbstractInstaller
{
    private ApplicationInterface $application;
    private Container $container;
    private Context $context;

    public function __construct(IOInterface $io, ?string $projectRoot = null)
    {
        parent::__construct($io, $projectRoot);

        $this->container = new Container();
        $applicationType = $this->config[$this->getApplicationType()] ?? null;
        if (!$applicationType instanceof ApplicationInterface) {
            throw new \InvalidArgumentException('Invalid application type!');
        }
        $this->application = $applicationType;

        if ($this->application instanceof AbstractApplication) {
            $this->application->setInstalled($this->composerDefinition['extra']['spiral'] ?? []);
        }

        $this->setContext();
    }

    public static function configure(Event $event): void
    {
        $conf = new self($event->getIO());

        $conf->runGenerators();
        $conf->createRoadRunnerConfig();
        $conf->runCommands();
        $conf->showInstructions();

        $conf->updateReadme();

        // We don't need MIT license file in the application, that's why we remove it.
        $conf->removeLicense();

        $conf->removeInstaller();
        $conf->finalize();
    }

    private function runGenerators(): void
    {
        foreach ($this->application->getGenerators() as $generator) {
            if (!$generator instanceof GeneratorInterface) {
                $generator = $this->container->get($generator);
            }

            $generator->process($this->context);
        }
    }

    private function getApplicationType(): int
    {
        return $this->composerDefinition['extra']['spiral']['application-type'] ?? 1;
    }

    private function setContext(): void
    {
        $this->context = new Context(
            application: $this->application,
            kernel: new KernelConfigurator(Kernel::class),
            exceptionHandlerBootloader: new ExceptionHandlerBootloaderConfigurator(ExceptionHandlerBootloader::class),
            envConfigurator: new EnvConfigurator($this->projectRoot, $this->resource),
            applicationRoot: $this->projectRoot,
            resource: $this->resource
        );
    }

    private function runCommands(): void
    {
        (new BashCommandExecutor($this->io))
            ->execute($this->application->getCommands());
    }

    private function createRoadRunnerConfig(): void
    {
        (new RoadRunnerConfigGenerator($this->io))
            ->generate($this->application->getRoadRunnerPlugins());
    }

    private function updateReadme(): void
    {
        (new ReadmeGenerator(
            $this->projectRoot . '/README.md',
            $this->application
        ))->generate();
    }

    private function showInstructions(): void
    {
        (new InstallationInstructionRenderer($this->io, $this->application))
            ->render();
    }

    private function removeLicense(): void
    {
        \unlink($this->projectRoot . 'LICENSE');
    }

    private function removeInstaller(): void
    {
        $this->io->info('Removing Configurator from composer.json ...');

        unset(
            $this->composerDefinition['scripts']['post-install-cmd'],
            $this->composerDefinition['scripts']['post-update-cmd'],
            $this->composerDefinition['extra']['spiral']
        );

        $this->io->success('Removing Installer files ...');
        $this->recursiveRmdir($this->projectRoot . 'installer');
        \unlink($this->projectRoot . 'cleanup.sh');
    }

    private function finalize(): void
    {
        $this->composerDefinition['autoload'] = $this->application->getAutoload();
        $this->composerDefinition['autoload-dev'] = $this->application->getAutoloadDev();

        $this->composerJson->write($this->composerDefinition);
    }
}
