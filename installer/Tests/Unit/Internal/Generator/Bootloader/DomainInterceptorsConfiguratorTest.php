<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Bootloader;

use Installer\Internal\Generator\Bootloader\DomainInterceptorsConfigurator;
use Installer\Internal\ReflectionClassMetadata;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;
use Tests\Fixtures\RoutesBootloader;
use Tests\TestCase;

final class DomainInterceptorsConfiguratorTest extends TestCase
{
    private FilesInterface|\Mockery\MockInterface $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = \Mockery::mock(FilesInterface::class);
    }

    public function testGenerateDefault(): void
    {
        $configurator = new DomainInterceptorsConfigurator(
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

                    use Spiral\Core\CoreInterface;

                    final class RoutesBootloader
                    {
                        protected const SINGLETONS = [CoreInterface::class => [self::class, 'domainCore']];
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }

    public function testGenerateWithInterceptors(): void
    {
        $configurator = new DomainInterceptorsConfigurator(
            new Writer($this->files),
            new ReflectionClassMetadata(RoutesBootloader::class),
        );

        $configurator->addInterceptor('App\Interceptor\FirstInterceptor');
        $configurator->addInterceptor('Tests\Fixtures\SecondInterceptor');

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/RoutesBootloader.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use App\Interceptor\FirstInterceptor;
                    use Spiral\Core\CoreInterface;

                    final class RoutesBootloader
                    {
                        protected const SINGLETONS = [CoreInterface::class => [self::class, 'domainCore']];

                        protected const INTERCEPTORS = [
                            FirstInterceptor::class,
                            SecondInterceptor::class,
                        ];
                    }

                    PHP;
            });

        $this->assertTrue(true);
        unset($configurator);
    }
}
