<?php

declare(strict_types=1);

namespace Installer;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Kernel;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Installer\Application\ApplicationInterface;
use Installer\Generator\Context;
use Installer\Generator\ExceptionHandlerBootloaderConfigurator;
use Installer\Generator\GeneratorInterface;
use Installer\Generator\KernelConfigurator;
use Installer\Package\Package;
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

        $this->setContext();
    }

    public static function configure(Event $event): void
    {
        $conf = new self($event->getIO());

        $conf->resource->createEnv();
        $conf->runGenerators();
        $conf->createRoadRunnerConfig();
        $conf->runCommands();
        $conf->removeInstaller();
        $conf->finalize();
    }

    private function runGenerators(): void
    {
        foreach ($this->application->getGenerators() as $package => $generator) {
            if ($package instanceof Package && !$this->isPackageInstalled($package)) {
                continue;
            }

            if (!$generator instanceof GeneratorInterface) {
                $generator = $this->container->get($generator);
            }

            $generator->process($this->context);
        }
    }

    private function isPackageInstalled(Package $package): bool
    {
        return \in_array($package->getName(), $this->getExtraPackages(), true);
    }

    /**
     * @return non-empty-string[]
     */
    private function getExtraPackages(): array
    {
        return $this->composerDefinition['extra']['spiral']['packages'] ?? [];
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
            applicationRoot: $this->projectRoot,
            resource: $this->resource,
            composerDefinition: $this->composerDefinition
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

    private function removeInstaller(): void
    {
        $this->io->write('<info>Remove Configurator from composer.json</info>');

        unset(
            $this->composerDefinition['scripts']['post-install-cmd'],
            $this->composerDefinition['scripts']['post-update-cmd']
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
