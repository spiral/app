<?php

declare(strict_types=1);

namespace Tests;

use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;
use Tests\Module\AbstractModule;

final class InstallationModuleResult
{
    /**
     * @var array<AbstractModule>
     */
    private array $modules = [];

    /**
     * @param array<AbstractModule> $installedModules
     */
    public function __construct(
        private readonly array $installedModules
    ) {
        $this->collectModules();
    }

    public function runTests(InstallationResult $result): void
    {
        $this->testInstalledModules($result);
        $this->testNotInstalledModules($result);
    }

    private function testInstalledModules(InstallationResult $result): void
    {
        foreach ($this->installedModules as $module) {
            $result->assertPackageInstalled($module->getPackage());

            foreach ($module->getGenerators() as $generator) {
                $result->assertGeneratorProcessed($generator);
            }

            foreach ($module->getBootloaders() as $bootloader) {
                $result->assertBootloaderRegistered($bootloader);
            }

            foreach ($module->getCopiedResources() as $path => $destination) {
                $result->assertCopied($path, $destination);
            }

            foreach ($module->getRemovedResources() as $path) {
                $result->assertDeleted($path);
            }

            foreach ($module->getMiddleware() as $middleware) {
                $result->assertMiddlewareRegistered($middleware);
            }

            foreach ($module->getEnvironmentVariables() as $name => $value) {
                $result->assertEnvDefined($name, $value);
            }
        }
    }

    private function testNotInstalledModules(InstallationResult $result): void
    {
        foreach ($this->modules as $module) {
            if ($this->isModuleInstalled($module)) {
                continue;
            }

            $result->assertPackageNotInstalled($module->getPackage());

            foreach ($module->getGenerators() as $generator) {
                $result->assertGeneratorNotProcessed($generator);
            }

            foreach ($module->getBootloaders() as $bootloader) {
                $result->assertBootloaderNotRegistered($bootloader);
            }

            foreach ($module->getCopiedResources() as $path => $_) {
                $result->assertNotCopied($path);
            }

            foreach ($module->getRemovedResources() as $path) {
                $result->assertFileExists($path);
            }

            foreach ($module->getMiddleware() as $middleware) {
                $result->assertMiddlewareNotRegistered($middleware);
            }

            foreach ($module->getEnvironmentVariables() as $name => $_) {
                $result->assertEnvNotDefined($name);
            }
        }
    }

    private function isModuleInstalled(AbstractModule $module): bool
    {
        foreach ($this->installedModules as $installedModule) {
            if ($installedModule::class === $module::class) {
                return true;
            }
        }

        return false;
    }

    private function collectModules(): void
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/Module')->name('*.php');

        $locator = new ClassLocator($finder);

        foreach ($locator->getClasses() as $class) {
            if ($class->isSubclassOf(AbstractModule::class) && !$class->isAbstract()) {
                $this->modules[] = $class->newInstance();
            }
        }
    }
}
