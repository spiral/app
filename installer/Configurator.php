<?php

declare(strict_types=1);

namespace Installer;

use Composer\Script\Event;
use Installer\Application\ApplicationInterface;
use Installer\Package\Generator\Context;
use Installer\Package\Generator\GeneratorInterface;
use Installer\Package\Generator\KernelConfigurator;
use Installer\Package\Package;
use Spiral\Core\Container;

final class Configurator extends AbstractInstaller
{
    private ApplicationInterface $application;
    private Container $container;
    private Context $context;
    /**
     * @var Package[]
     */
    private array $installedPackages = [];

    public static function configure(Event $event): void
    {
        $conf = new self($event->getIO());

        $conf->container = new Container();
        $conf->application = $conf->config[$conf->composerDefinition['extra']['spiral']['application-type']];
        $conf->setInstalledPackages();
        $conf->setContext();
        $conf->runGenerators();
    }

    private function runGenerators(): void
    {
        foreach ($this->installedPackages as $package) {
            foreach ($package->getGenerators() as $generator) {
                if (!$generator instanceof GeneratorInterface) {
                    $generator = $this->container->get($generator);
                }
                $generator->process($this->context);
            }
        }
    }

    private function isPackageInstalled(Package $package): bool
    {
        return \in_array($package->getName(), $this->composerDefinition['extra']['spiral']['packages'], true);
    }

    private function setInstalledPackages(): void
    {
        foreach ($this->application->getQuestions() as $question) {
            foreach ($question->getOptions() as $option) {
                foreach ($option->getPackages() as $package) {
                    if ($this->isPackageInstalled($package)) {
                        $this->installedPackages[] = $package;
                    }
                }
            }
        }
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
}
