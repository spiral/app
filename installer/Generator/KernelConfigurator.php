<?php

declare(strict_types=1);

namespace Installer\Generator;

use App\Application\Bootloader\ExceptionHandlerBootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader\CommandBootloader;
use Spiral\Bootloader\SnapshotsBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Files\Files;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;

final class KernelConfigurator
{
    private FileDeclaration $declaration;
    private \ReflectionClass $reflection;
    private PhpNamespace $namespace;

    /**
     * @param class-string $kernelClass
     */
    public function __construct(
        string $kernelClass,
        public readonly Bootloaders $system = new Bootloaders(BootloaderPlaces::System),
        public readonly Bootloaders $load = new Bootloaders(BootloaderPlaces::Load),
        public readonly Bootloaders $app = new Bootloaders(BootloaderPlaces::App)
    ) {
        $this->reflection = new \ReflectionClass($kernelClass);
        $this->declaration = FileDeclaration::fromCode(\file_get_contents($this->reflection->getFileName()));
        $this->namespace = $this->declaration->getNamespaces()->get($this->reflection->getNamespaceName());

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

        (new Writer(new Files()))->write($this->reflection->getFileName(), $this->declaration);
    }

    public function addUse(string $name, ?string $alias = null): void
    {
        $this->namespace->addUse($name, $alias);
    }

    private function addRequiredSystemBootloaders(): void
    {
        $this->addUse(CoreBootloader::class);
        $this->addUse(TokenizerBootloader::class);
        $this->addUse(DotenvBootloader::class);

        $this->system->addGroup(
            bootloaders: [
                CoreBootloader::class,
                TokenizerBootloader::class,
                DotenvBootloader::class,
            ],
        );
    }

    private function addRequiredLoadBootloaders(): void
    {
        $this->addUse(MonologBootloader::class);
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
                SnapshotsBootloader::class,
            ],
            comment: 'Core Services',
            priority: 4
        );

        $this->load->addGroup(
            bootloaders: [
                CommandBootloader::class,
            ],
            comment: 'Console commands',
            priority: 14
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
