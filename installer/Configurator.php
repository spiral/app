<?php

declare(strict_types=1);

namespace Installer;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use Installer\Application\ApplicationInterface;
use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Installer\Package\Generator\KernelConfigurator;
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

        $conf->runGenerators();
        $conf->runCommands();
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
            kernel: new KernelConfigurator($this->application->getKernelClass()),
            applicationRoot: $this->projectRoot,
            composerDefinition: $this->composerDefinition
        );
    }

    private function runCommands(): void
    {
        foreach ($this->application->getCommands() as $command) {
            (new Process(\explode(' ', $command)))->run(function ($type, $data) {
                $this->io->write($data);
            });
        }
    }
}
