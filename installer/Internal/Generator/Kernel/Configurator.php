<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Bootloader\LoggingBootloader;
use Installer\Internal\Generator\AbstractConfigurator;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\SnapshotsBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Reactor\Writer;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;

final class Configurator extends AbstractConfigurator
{
    /**
     * @param class-string $kernelClass
     */
    public function __construct(
        string $kernelClass,
        Writer $writer,
        public readonly Bootloaders $system = new MethodBasedBootloaders(BootloaderPlaces::System),
        public readonly Bootloaders $load = new MethodBasedBootloaders(BootloaderPlaces::Load),
        public readonly Bootloaders $app = new MethodBasedBootloaders(BootloaderPlaces::App)
    ) {
        parent::__construct($kernelClass, $writer);

        $this->addRequiredSystemBootloaders();
        $this->addRequiredLoadBootloaders();
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
        $this->addUse(PrototypeBootloader::class);

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
                PrototypeBootloader::class,
            ],
            comment: 'Fast code prototyping',
            priority: 1000
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
}
