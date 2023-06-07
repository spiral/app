<?php

declare(strict_types=1);

namespace Tests;

use Installer\Internal\Events\BootloadersInjected;
use Installer\Internal\Events\ConstantInjected;
use Installer\Internal\Events\CopyEvent;
use Installer\Internal\Events\DeleteEvent;
use Installer\Internal\Events\EnvGenerated;
use Installer\Internal\Events\MiddlewareInjected;
use Installer\Internal\Events\PackageRegistered;
use Installer\Internal\Events\ReadmeGenerated;
use Installer\Internal\Generator\Bootloader\ClassName;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Internal\Generator\Kernel\BootloaderPlaces;
use Installer\Internal\Process\Output;
use PHPUnit\Framework\Assert;
use Spiral\Files\FilesInterface;

final class InstallationResult
{
    public function __construct(
        private readonly FilesInterface $files,
        public readonly string $appName,
        private readonly string $rootPath,
        public readonly string $log,
        private readonly array $events,
        ?bool $testsSuccess = null,
    ) {
        if ($testsSuccess !== null) {
            Assert::assertTrue($testsSuccess, 'Application tests execution failure');
        }
    }

    public function assertBootloaderNotRegistered(string $class, ?BootloaderPlaces $place = null): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof BootloadersInjected && (!$place || $event->place === $place)) {
                foreach ($event->group as $group) {
                    if ($group->hasClass($class)) {
                        Assert::fail(
                            \sprintf('Bootloader "%s" was registered in "%s" section', $class, $event->place->value)
                        );
                    }
                }
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertBootloaderRegistered(string $class, ?BootloaderPlaces $place = null): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof BootloadersInjected && (!$place || $event->place === $place)) {
                foreach ($event->group as $group) {
                    if ($group->hasClass($class)) {
                        Assert::assertTrue(true);

                        return $this;
                    }
                }
            }
        }

        Assert::fail(\sprintf('Bootloader "%s" was not registered in "%s" section', $class, $event->place->value));
    }

    public function assertInterceptorNotRegistered(string $interceptor): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof ConstantInjected && $event->constant->name === 'INTERCEPTORS' && $event->constant) {
                foreach ($event->constant->getValues() as $value) {
                    if ($value instanceof ClassName && $value->class === $interceptor) {
                        Assert::fail(\sprintf('Interceptor "%s" was registered', $interceptor));
                    }
                }
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertInterceptorRegistered(string $interceptor): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof ConstantInjected && $event->constant->name === 'INTERCEPTORS' && $event->constant) {
                foreach ($event->constant->getValues() as $value) {
                    if ($value instanceof ClassName && $value->class === $interceptor) {
                        Assert::assertTrue(true);

                        return $this;
                    }
                }
            }
        }

        Assert::fail(\sprintf('Interceptor "%s" was not registered', $interceptor));
    }

    public function assertMiddlewareNotRegistered(string $middleware, string $group = 'global'): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof MiddlewareInjected
                && $event->group === $group
                && $event->middleware->hasClass($middleware)
            ) {
                Assert::fail(\sprintf('Middleware "%s" was registered in "%s" group', $middleware, $group));
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertMiddlewareRegistered(string $middleware, string $group = 'global'): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof MiddlewareInjected
                && $event->group === $group
                && $event->middleware->hasClass($middleware)
            ) {
                Assert::assertTrue(true);

                return $this;
            }
        }

        Assert::fail(\sprintf('Middleware "%s" was not registered in "%s" group', $middleware, $group));
    }

    public function assertEnvNotDefined(string $name): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof EnvGenerated) {
                foreach ($event->envs as $group) {
                    foreach ($group as $key => $v) {
                        if ($key === $name) {
                            Assert::fail(\sprintf('Env "%s" was defined with value "%s"', $name, $v));
                        }
                    }
                }
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertEnvDefined(string $name, string $value): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof EnvGenerated) {
                foreach ($event->envs as $group) {
                    foreach ($group as $key => $v) {
                        if ($key === $name) {
                            if ($v === $value) {
                                Assert::assertTrue(true);

                                return $this;
                            }

                            Assert::fail(
                                \sprintf('Env "%s" was defined with value "%s" instead of "%s"', $name, $v, $value)
                            );
                        }
                    }
                }
            }
        }

        Assert::fail(\sprintf('Env "%s" was not defined', $name));
    }

    public function assertPackageNotInstalled(string $package): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof PackageRegistered && $event->name === $package) {
                Assert::fail(\sprintf('Package "%s" was not installed', $package));
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertPackageInstalled(string $package, ?string $version = null): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof PackageRegistered && $event->name === $package) {
                if (!$version || $event->version === $version) {
                    Assert::assertTrue(true);

                    return $this;
                }

                Assert::fail(
                    \sprintf(
                        'Package "%s" was installed with version "%s" instead of "%s"',
                        $package,
                        $event->version,
                        $version
                    )
                );
            }
        }

        Assert::fail(\sprintf('Package "%s" was not installed', $package));
    }

    public function assertGeneratorProcessed(string $class): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof GeneratorInterface && $event instanceof $class) {
                Assert::assertTrue(true);

                return $this;
            }
        }

        Assert::fail(\sprintf('Generator "%s" was not processed', $class));
    }

    public function assertGeneratorNotProcessed(string $class): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof GeneratorInterface && $event instanceof $class) {
                Assert::fail(\sprintf('Generator "%s" was processed', $class));
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertMessageShown(string $message): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof \Installer\Internal\Console\Output && \str_ends_with((string)$event, $message)) {
                Assert::assertTrue(true);

                return $this;
            }
        }

        Assert::fail(\sprintf('Message "%s" was not shown', $message));
    }

    public function assertCommandExecuted(string $command): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof Output && $event->command === $command) {
                Assert::assertTrue(true);

                return $this;
            }
        }

        Assert::fail(\sprintf('Command "%s" was not executed', $command));
    }

    public function assertCommandNotExecuted(string $command): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof Output && $event->command === $command) {
                Assert::fail(\sprintf('Command "%s" was not executed', $command));
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertNotCopied(string $path): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof CopyEvent) {
                if (\str_ends_with($event->getFullSource(), $path)) {
                    Assert::fail(\sprintf('File "%s" was copied', $path));
                }
            }
        }

        Assert::assertTrue(true);

        return $this;
    }

    public function assertDeleted(string $file): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof DeleteEvent) {
                if (\str_ends_with($event->path, $file)) {
                    Assert::assertTrue(true);

                    return $this;
                }
            }
        }

        Assert::fail(\sprintf('File "%s" was not deleted', $file));
    }

    public function assertFileExists(string $path): self
    {
        Assert::assertFileExists($this->getPath() . '/' . \ltrim($path));

        return $this;
    }

    public function assertFileNotExists(string $path): self
    {
        Assert::assertFileDoesNotExist($this->getPath() . '/' . \ltrim($path));

        return $this;
    }

    public function assertCopied(string $path, string $destination): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof CopyEvent) {
                if (\str_ends_with($event->getFullSource(), $path)) {
                    if (\str_ends_with($event->getFullDestination(), $destination)) {
                        Assert::assertTrue(true);

                        return $this;
                    }

                    Assert::fail(
                        \sprintf(
                            'File "%s" was copied to "%s" instead of "%s"',
                            $path,
                            $event->getFullDestination(),
                            $destination
                        )
                    );
                }
            }
        }

        Assert::fail(\sprintf('File "%s" was not copied to "%s"', $path, $destination));
    }

    public function assertReadmeContains(string $text): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof ReadmeGenerated) {
                Assert::assertStringContainsString($text, $event->content);
            }
        }

        return $this;
    }

    public function assertReadmeNotContains(string $text): self
    {
        foreach ($this->events as $event) {
            if ($event instanceof ReadmeGenerated) {
                Assert::assertStringNotContainsString($text, $event->content);
            }
        }

        return $this;
    }

    public function storeLog(): void
    {
        $this->files->write(
            filename: \sprintf('%s/logs/install-%s.log', $this->rootPath, $this->appName),
            data: $this->log,
            ensureDirectory: true
        );
    }

    public function cleanup(): void
    {
        $this->files->deleteDirectory($this->getPath());
    }

    private function getPath(): string
    {
        return $this->rootPath . '/' . $this->appName;
    }
}
