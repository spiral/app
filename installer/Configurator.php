<?php

declare(strict_types=1);

namespace Installer;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Kernel;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Installer\Application\AbstractApplication;
use Installer\Application\ApplicationInterface;
use Installer\Generator\Context;
use Installer\Generator\EnvConfigurator;
use Installer\Generator\ExceptionHandlerBootloaderConfigurator;
use Installer\Generator\GeneratorInterface;
use Installer\Generator\KernelConfigurator;
use Installer\Package\Package;
use Installer\Question\Option\Option;
use Spiral\Core\Container;
use Symfony\Component\Process\Process;

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
        foreach ($this->application->getCommands() as $command) {
            (new Process(\explode(' ', $command)))->run(function (string $type, mixed $data) {
                $this->io->write($data);
            });
        }
    }

    private function createRoadRunnerConfig(): void
    {
        $plugins = '';
        $rrPlugins = $this->application->getRoadRunnerPlugins();

        if (\count($rrPlugins) > 0) {
            $plugins = ' -p ' . \implode(' -p ', $rrPlugins);
        }

        (new Process(\explode(' ', 'rr make-config' . $plugins)))->run(function (string $type, mixed $data) {
            $this->io->write($data);
        });
    }

    private function showInstructions(): void
    {
        $this->io->write('  <comment>Next steps:</comment>');

        // from application
        foreach ($this->application->getInstructions() as $index => $instruction) {
            $this->io->write(\sprintf('  %s. %s', (int) $index + 1, $instruction));
        }

        $showPackageInstruction = function (Package $package): void {
            if ($package->getInstructions() !== []) {
                $this->io->write(\sprintf('  <comment>%s</comment>', $package->getTitle()));
            }

            foreach ($package->getInstructions() as $index => $instruction) {
                $this->io->write(\sprintf('  %s. %s', (int) $index + 1, $instruction));
            }
        };

        // from required packages
        foreach ($this->application->getPackages() as $package) {
            $showPackageInstruction($package);
        }

        // from installed optional packages
        foreach ($this->application->getQuestions() as $question) {
            foreach ($question->getOptions() as $option) {
                foreach ($option instanceof Option ? $option->getPackages() : [] as $package) {
                    if ($this->application->isPackageInstalled($package)) {
                        $showPackageInstruction($package);
                    }
                }
            }
        }
    }

    private function removeInstaller(): void
    {
        $this->io->write('<info>Remove Configurator from composer.json</info>');

        unset(
            $this->composerDefinition['scripts']['post-install-cmd'],
            $this->composerDefinition['scripts']['post-update-cmd'],
            $this->composerDefinition['extra']['spiral']
        );

        $this->io->write('<info>Remove Installer files</info>');
        $this->recursiveRmdir($this->projectRoot . 'installer');
    }

    private function finalize(): void
    {
        $this->composerDefinition['autoload'] = $this->application->getAutoload();
        $this->composerDefinition['autoload-dev'] = $this->application->getAutoloadDev();

        $this->composerJson->write($this->composerDefinition);
    }
}
