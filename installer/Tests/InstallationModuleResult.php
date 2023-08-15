<?php

declare(strict_types=1);

namespace Tests;

use Installer\Internal\Application\ApplicationInterface;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;
use Tests\Module\AbstractModule;

final class InstallationModuleResult
{
    /**
     * @var array<AbstractModule>
     */
    private array $modules = [];

    private array $registeredBootloaders = [];

    /**
     * @param array<AbstractModule> $installedModules
     */
    public function __construct(
        private readonly array $installedModules
    ) {
        $this->collectModules();
    }

    public function runTests(InstallationResult $result, ApplicationInterface $application): void
    {
        $this->testInstalledModules($result, $application);
        $this->testNotInstalledModules($result, $application);
    }

    private function testInstalledModules(InstallationResult $result, ApplicationInterface $application): void
    {
        foreach ($this->installedModules as $module) {
            if ($module->getPackage() !== null) {
                $result->assertPackageInstalled($module->getPackage());
            }

            foreach ($module->getGenerators($application) as $generator) {
                $result->assertGeneratorProcessed($generator);
            }

            foreach ($module->getBootloaders($application) as $bootloader) {
                $result->assertBootloaderRegistered($bootloader);
                $this->registeredBootloaders[] = $bootloader;
            }

            if ($module->getResourcesPath() !== null) {
                foreach ($module->getCopiedResources($application) as $path => $destination) {
                    $result->assertCopied(
                        \rtrim($module->getResourcesPath(), '/') . '/'. \ltrim($path, '/'),
                        $destination
                    );
                }
            }

            foreach ($module->getRemovedResources($application) as $path) {
                $result->assertDeleted($path);
            }

            foreach ($module->getMiddleware($application) as $middleware) {
                $result->assertMiddlewareRegistered($middleware);
            }

            foreach ($module->getInterceptors($application) as $interceptor) {
                $result->assertInterceptorRegistered($interceptor);
            }

            foreach ($module->getEnvironmentVariables($application) as $name => $value) {
                $result->assertEnvDefined($name, $value);
            }
        }
    }

    private function testNotInstalledModules(InstallationResult $result, ApplicationInterface $application): void
    {
        foreach ($this->modules as $module) {
            if ($this->isModuleInstalled($module)) {
                continue;
            }

            if ($module->getPackage() !== null) {
                $result->assertPackageNotInstalled($module->getPackage());
            }

            foreach ($module->getGenerators($application) as $generator) {
                $result->assertGeneratorNotProcessed($generator);
            }

            foreach ($module->getBootloaders($application) as $bootloader) {
                if (!\in_array($bootloader, $this->registeredBootloaders, true)) {
                    $result->assertBootloaderNotRegistered($bootloader);
                }
            }

            if ($module->getResourcesPath() !== null) {
                foreach ($module->getCopiedResources($application) as $path => $_) {
                    $result->assertNotCopied($module->getResourcesPath() . $path);
                }
            }

            foreach ($module->getRemovedResources($application) as $path) {
                $result->assertFileExists($path);
            }

            foreach ($module->getMiddleware($application) as $middleware) {
                $result->assertMiddlewareNotRegistered($middleware);
            }

            foreach ($module->getInterceptors($application) as $interceptor) {
                $result->assertInterceptorNotRegistered($interceptor);
            }

            foreach ($module->getEnvironmentVariables($application) as $name => $_) {
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
