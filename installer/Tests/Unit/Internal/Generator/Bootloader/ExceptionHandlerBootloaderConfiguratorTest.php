<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\ExceptionHandlerBootloaderConfigurator;
use Installer\Internal\ReflectionClassMetadata;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;
use Tests\Fixtures\ExceptionHandlerBootloader;
use Tests\TestCase;

final class ExceptionHandlerBootloaderConfiguratorTest extends TestCase
{
    private FilesInterface|\Mockery\MockInterface $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = \Mockery::mock(FilesInterface::class);
    }

    public function testBindingsWithoutConstant(): void
    {
        $configurator = new ExceptionHandlerBootloaderConfigurator(
            new Writer($this->files),
            new ReflectionClassMetadata(ExceptionHandlerBootloader::class),
        );

        $configurator->addBinding('Foo', 'Bar');

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/ExceptionHandlerBootloader.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Bar;
                    use Foo;
                    use Spiral\Http\Middleware\ErrorHandlerMiddleware\EnvSuppressErrors;
                    use Spiral\Http\Middleware\ErrorHandlerMiddleware\SuppressErrorsInterface;

                    final class ExceptionHandlerBootloader
                    {
                        protected const BINDINGS = [
                            SuppressErrorsInterface::class => EnvSuppressErrors::class,
                            Foo::class => Bar::class,
                        ];
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }
}
