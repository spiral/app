<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Kernel;

use Installer\Internal\Generator\Kernel\Configurator;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;
use Tests\Fixtures\Kernel;
use Tests\TestCase;

final class ConfiguratorTest extends TestCase
{
    private FilesInterface|\Mockery\MockInterface $files;
    private Configurator $configurator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = \Mockery::mock(FilesInterface::class);

        $this->configurator = new Configurator(
            Kernel::class,
            new Writer($this->files),
        );
    }

    public function testGenerateWithCustomBootloaders(): void
    {
        $this->configurator->app->append('App\Application\Bootloader\LoggingBootloader');
        $this->configurator->load->append('Spiral\Scaffolder\Bootloader\ScaffolderBootloader1');
        $this->configurator->system->append('Spiral\Bootloader\CommandBootloader');

        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/Kernel.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Spiral\Boot\Bootloader\CoreBootloader;
                    use Spiral\Bootloader as Framework;
                    use Spiral\DotEnv\Bootloader\DotenvBootloader;
                    use Spiral\Monolog\Bootloader\MonologBootloader;
                    use Spiral\Prototype\Bootloader\PrototypeBootloader;
                    use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
                    use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;

                    final class Kernel
                    {
                        public function defineSystemBootloaders(): array
                        {
                            return [
                                CoreBootloader::class,
                                TokenizerListenerBootloader::class,
                                DotenvBootloader::class,

                                Framework\CommandBootloader::class,
                            ];
                        }

                        public function defineBootloaders(): array
                        {
                            return [
                                // Logging and exceptions handling
                                MonologBootloader::class,
                                \App\Application\Bootloader\ExceptionHandlerBootloader::class,

                                // Application specific logs
                                \App\Application\Bootloader\LoggingBootloader::class,

                                // Core Services
                                Framework\SnapshotsBootloader::class,

                                \Spiral\Scaffolder\Bootloader\ScaffolderBootloader1::class,

                                // Console commands
                                Framework\CommandBootloader::class,
                                ScaffolderBootloader::class,

                                // Fast code prototyping
                                PrototypeBootloader::class,
                            ];
                        }

                        public function defineAppBootloaders(): array
                        {
                            return [
                                \App\Application\Bootloader\LoggingBootloader::class,
                            ];
                        }
                    }

                    PHP;
            });

        unset($this->configurator);

        $this->assertTrue(true);
    }

    public function testGenerateWithDefaultBootloaders(): void
    {
        $this->files->shouldReceive('write')
            ->withArgs(static function (string $filename, string $data): bool {
                if (!\str_ends_with($filename, 'Fixtures/Kernel.php')) {
                    return false;
                }

                return $data === <<<'PHP'
                    <?php

                    declare(strict_types=1);

                    namespace Tests\Fixtures;

                    use Spiral\Boot\Bootloader\CoreBootloader;
                    use Spiral\Bootloader as Framework;
                    use Spiral\DotEnv\Bootloader\DotenvBootloader;
                    use Spiral\Monolog\Bootloader\MonologBootloader;
                    use Spiral\Prototype\Bootloader\PrototypeBootloader;
                    use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
                    use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;

                    final class Kernel
                    {
                        public function defineSystemBootloaders(): array
                        {
                            return [
                                CoreBootloader::class,
                                TokenizerListenerBootloader::class,
                                DotenvBootloader::class,
                            ];
                        }

                        public function defineBootloaders(): array
                        {
                            return [
                                // Logging and exceptions handling
                                MonologBootloader::class,
                                \App\Application\Bootloader\ExceptionHandlerBootloader::class,

                                // Application specific logs
                                \App\Application\Bootloader\LoggingBootloader::class,

                                // Core Services
                                Framework\SnapshotsBootloader::class,

                                // Console commands
                                Framework\CommandBootloader::class,
                                ScaffolderBootloader::class,

                                // Fast code prototyping
                                PrototypeBootloader::class,
                            ];
                        }
                    }

                    PHP;
            });

        unset($this->configurator);

        $this->assertTrue(true);
    }
}
