<?php

declare(strict_types=1);

namespace Installer\Generator;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Bootloader\LoggingBootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\SnapshotsBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;

final class KernelConfigurator extends AbstractConfigurator
{
    /**
     * @param class-string $kernelClass
     */
    public function __construct(
        string $kernelClass,
        public readonly Bootloaders $system = new Bootloaders(BootloaderPlaces::System),
        public readonly Bootloaders $load = new Bootloaders(BootloaderPlaces::Load),
        public readonly Bootloaders $app = new Bootloaders(BootloaderPlaces::App)
    ) {
        parent::__construct($kernelClass);

        $this->addRequiredSystemBootloaders();
        $this->addRequiredLoadBootloaders();
        $this->addRequiredAppBootloaders();
    }

    public function __destruct()
    {
        /** @var Bootloaders $bootloaders */
        foreach ([$this->system, $this->load, $this->app] as $bootloaders) {
            $bootloaders->updateDeclaration(
                $this->declaration->getClass($this->reflection->getName()),
                $this->namespace
            );
        }

        $this->write();
    }

    private function addRequiredSystemBootloaders(): void
    {
        $this->addUse(CoreBootloader::class);
        $this->addUse(TokenizerListenerBootloader::class);
        $this->addUse(DotenvBootloader::class);

        $this->system->addGroup(
            bootloaders: [
                CoreBootloader::class,
                TokenizerListenerBootloader::class,
                DotenvBootloader::class,
            ],
        );
    }

    private function addRequiredLoadBootloaders(): void
    {
        $this->addUse(MonologBootloader::class);
        $this->addUse(ScaffolderBootloader::class);
        $this->addUse('Spiral\Bootloader', 'Framework');

        $this->load->addGroup(
            bootloaders: [
                MonologBootloader::class,
                ExceptionHandlerBootloader::class,
            ],
            comment: 'Logging and exceptions handling',
        );

        $this->load->addGroup(
            bootloaders: [
                LoggingBootloader::class,
            ],
            comment: 'Application specific logs',
            priority: 1
        );

        $this->load->addGroup(
            bootloaders: [
                SnapshotsBootloader::class,
            ],
            comment: 'Core Services',
            priority: 4
        );

        $this->load->addGroup(
            bootloaders: [
                CommandBootloader::class,
                ScaffolderBootloader::class,
            ],
            comment: 'Console commands',
            priority: 101
        );
    }

    private function addRequiredAppBootloaders(): void
    {
        $this->addUse(PrototypeBootloader::class);

        $this->app->addGroup(
            bootloaders: [
                PrototypeBootloader::class,
            ],
            comment: 'Fast code prototyping',
        );
    }
}
