<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\RoutesBootloaderConfigurator;
use Installer\Internal\ReflectionClassMetadata;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;
use Tests\Fixtures\RoutesBootloader;
use Tests\TestCase;

final class RoutesBootloaderConfiguratorTest extends TestCase
{
    private FilesInterface|\Mockery\MockInterface $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = \Mockery::mock(FilesInterface::class);
    }

    public function testInjectDefaultMiddleware(): void
    {
        $configurator = new RoutesBootloaderConfigurator(
            new Writer($this->files),
            new ReflectionClassMetadata(RoutesBootloader::class),
        );

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/RoutesBootloader.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Spiral\Cookies\Middleware\CookiesMiddleware;
                    use Spiral\Csrf\Middleware\CsrfMiddleware;
                    use Spiral\Debug\StateCollector\HttpCollector;
                    use Spiral\Http\Middleware\ErrorHandlerMiddleware;
                    use Spiral\Http\Middleware\JsonPayloadMiddleware;
                    use Spiral\Session\Middleware\SessionMiddleware;

                    final class RoutesBootloader
                    {
                        public function globalMiddleware(): array
                        {
                            return [
                                ErrorHandlerMiddleware::class,
                                JsonPayloadMiddleware::class,
                                HttpCollector::class,
                            ];
                        }

                        public function middlewareGroups(): array
                        {
                            return [
                                'web' => [
                                    CookiesMiddleware::class,
                                    SessionMiddleware::class,
                                    CsrfMiddleware::class
                                ],
                            ];
                        }
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }

    public function testAddGlobalMiddleware(): void
    {
        $configurator = new RoutesBootloaderConfigurator(
            new Writer($this->files),
            new ReflectionClassMetadata(RoutesBootloader::class),
        );

        $configurator->addGlobalMiddleware(['TestMiddleware'], \Spiral\Http\Middleware\JsonPayloadMiddleware::class);

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/RoutesBootloader.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Spiral\Cookies\Middleware\CookiesMiddleware;
                    use Spiral\Csrf\Middleware\CsrfMiddleware;
                    use Spiral\Debug\StateCollector\HttpCollector;
                    use Spiral\Http\Middleware\ErrorHandlerMiddleware;
                    use Spiral\Http\Middleware\JsonPayloadMiddleware;
                    use Spiral\Session\Middleware\SessionMiddleware;
                    use TestMiddleware;

                    final class RoutesBootloader
                    {
                        public function globalMiddleware(): array
                        {
                            return [
                                ErrorHandlerMiddleware::class,
                                JsonPayloadMiddleware::class,
                                TestMiddleware::class,
                                HttpCollector::class,
                            ];
                        }

                        public function middlewareGroups(): array
                        {
                            return [
                                'web' => [
                                    CookiesMiddleware::class,
                                    SessionMiddleware::class,
                                    CsrfMiddleware::class
                                ],
                            ];
                        }
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }

    public function testAddGroupMiddleware(): void
    {
        $configurator = new RoutesBootloaderConfigurator(
            new Writer($this->files),
            new ReflectionClassMetadata(RoutesBootloader::class),
        );

        $configurator->addMiddlewareGroup('api', ['TestMiddleware']);

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/RoutesBootloader.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Spiral\Cookies\Middleware\CookiesMiddleware;
                    use Spiral\Csrf\Middleware\CsrfMiddleware;
                    use Spiral\Debug\StateCollector\HttpCollector;
                    use Spiral\Http\Middleware\ErrorHandlerMiddleware;
                    use Spiral\Http\Middleware\JsonPayloadMiddleware;
                    use Spiral\Session\Middleware\SessionMiddleware;
                    use TestMiddleware;

                    final class RoutesBootloader
                    {
                        public function globalMiddleware(): array
                        {
                            return [
                                ErrorHandlerMiddleware::class,
                                JsonPayloadMiddleware::class,
                                HttpCollector::class,
                            ];
                        }

                        public function middlewareGroups(): array
                        {
                            return [
                                'web' => [
                                    CookiesMiddleware::class,
                                    SessionMiddleware::class,
                                    CsrfMiddleware::class
                                ],
                                'api' => [
                                    TestMiddleware::class
                                ],
                            ];
                        }
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }
}
