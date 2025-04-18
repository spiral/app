<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use App\Application\Bootloader\LoggingBootloader;
use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\EventStorage;
use Installer\Internal\Generator\AbstractConfigurator;
use Installer\Internal\ReflectionClassMetadata;
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
    public readonly Bootloaders $system;
    public readonly Bootloaders $load;
    public readonly Bootloaders $app;

    public function __construct(
        Writer $writer,
        ClassMetadataInterface $class = new ReflectionClassMetadata(Kernel::class),
        ?Bootloaders $system = null,
        ?Bootloaders $load = null,
        ?Bootloaders $app = null,
        ?EventStorage $eventStorage = null,
    ) {
        parent::__construct($class, $writer);

        $this->system = $system ?? new MethodBasedBootloaders(BootloaderPlaces::System, $eventStorage);
        $this->load = $load ?? new MethodBasedBootloaders(BootloaderPlaces::Load, $eventStorage);
        $this->app = $app ?? new MethodBasedBootloaders(BootloaderPlaces::App, $eventStorage);

        $this->addRequiredSystemBootloaders();
        $this->addRequiredLoadBootloaders();
    }

    public function __destruct()
    {
        /** @var Bootloaders $bootloaders */
        foreach ([$this->system, $this->load, $this->app] as $bootloaders) {
            $bootloaders->updateDeclaration(
                $this->declaration->getClass($this->class->getName()),
                $this->namespace,
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
                DotenvBootloader::class,
                TokenizerListenerBootloader::class,
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
            priority: 1,
        );

        $this->load->addGroup(
            bootloaders: [
                SnapshotsBootloader::class,
            ],
            comment: 'Core Services',
            priority: 4,
        );

        $this->load->addGroup(
            bootloaders: [
                PrototypeBootloader::class,
            ],
            comment: 'Fast code prototyping',
            priority: 1000,
        );

        $this->load->addGroup(
            bootloaders: [
                CommandBootloader::class,
                ScaffolderBootloader::class,
            ],
            comment: 'Console commands',
            priority: 101,
        );
    }
}
