<?php

declare(strict_types=1);

namespace Installer\Package\Generator;

use Nette\PhpGenerator\Literal;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Files\Files;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;

final class KernelConfigurator
{
    private FileDeclaration $declaration;
    private \ReflectionClass $reflection;
    private PhpNamespace $namespace;

    private array $system = [
        CoreBootloader::class,
        TokenizerBootloader::class,
        DotenvBootloader::class,
    ];

    private array $load = [
        MonologBootloader::class,
        Framework\SnapshotsBootloader::class,
        Framework\I18nBootloader::class,
        Framework\Security\EncrypterBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,
        Framework\CommandBootloader::class,
        ScaffolderBootloader::class,
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,
    ];

    private array $app = [
        PrototypeBootloader::class,
    ];

    /**
     * @param class-string $kernelClass
     */
    public function __construct(string $kernelClass)
    {
        $this->reflection = new \ReflectionClass($kernelClass);
        $this->declaration = FileDeclaration::fromCode(\file_get_contents($this->reflection->getFileName()));
        $this->namespace = $this->declaration->getNamespaces()->get($this->reflection->getNamespaceName());
    }

    public function systemAppend(string $bootloader, string $afterBootloader): void
    {
        $this->append($this->system, $bootloader, $afterBootloader);
    }

    public function loadAppend(string $bootloader, string $afterBootloader): void
    {
        $this->append($this->load, $bootloader, $afterBootloader);
    }

    public function appAppend(string $bootloader, string $afterBootloader): void
    {
        $this->append($this->app, $bootloader, $afterBootloader);
    }

    public function systemPrepend(string $bootloader, string $beforeBootloader): void
    {
        $this->prepend($this->system, $bootloader, $beforeBootloader);
    }

    public function loadPrepend(string $bootloader, string $beforeBootloader): void
    {
        $this->prepend($this->load, $bootloader, $beforeBootloader);
    }

    public function appPrepend(string $bootloader, string $beforeBootloader): void
    {
        $this->prepend($this->app, $bootloader, $beforeBootloader);
    }

    public function addUse(string $name, ?string $alias = null): void
    {
        $this->namespace->addUse($name, $alias);
    }

    public function __destruct()
    {
        $this->namespace->addUse(CoreBootloader::class);
        $this->namespace->addUse(TokenizerBootloader::class);
        $this->namespace->addUse(DotenvBootloader::class);
        $this->namespace->addUse(MonologBootloader::class);
        $this->namespace->addUse('Spiral\Bootloader', 'Framework');
        $this->namespace->addUse(ScaffolderBootloader::class);
        $this->namespace->addUse(PrototypeBootloader::class);

        $this->declaration
            ->getClass($this->reflection->getName())
            ->getConstant('SYSTEM')
            ->setValue(\array_map(fn (string $value) => $this->getFormatted($value), $this->system));
        $this->declaration
            ->getClass($this->reflection->getName())
            ->getConstant('LOAD')
            ->setValue(\array_map(fn (string $value) => $this->getFormatted($value), $this->load));
        $this->declaration
            ->getClass($this->reflection->getName())
            ->getConstant('APP')
            ->setValue(\array_map(fn (string $value) => $this->getFormatted($value), $this->app));

        (new Writer(new Files()))->write($this->reflection->getFileName(), $this->declaration);
    }

    private function getFormatted(mixed $value): string|Literal
    {
        if (\class_exists($value)) {
            return new Literal($this->namespace->simplifyName($value) . '::class' . PHP_EOL);
        }

        return $value;
    }

    private function append(array &$array, string $bootloader, string $afterBootloader): void
    {
        $founded = false;
        foreach ($array as $pos => $value) {
            if ($afterBootloader === $value) {
                $array = \array_merge(
                    \array_slice($array, 0, $pos + 1),
                    [$bootloader],
                    \array_slice($array, $pos + 1)
                );
                $founded = true;
                break;
            }
        }

        if ($founded === false) {
            $array[] = $bootloader;
        }
    }

    private function prepend(array &$array, string $bootloader, string $beforeBootloader): void
    {
        $founded = false;
        foreach ($array as $pos => $value) {
            if ($beforeBootloader === $value) {
                $array = \array_merge(
                    \array_slice($array, 0, $pos),
                    [$bootloader],
                    \array_slice($array, $pos)
                );
                $founded = true;
                break;
            }
        }

        if ($founded === false) {
            $array[] = $bootloader;
        }
    }
}
