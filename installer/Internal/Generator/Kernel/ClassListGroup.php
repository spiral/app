<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use Spiral\Reactor\Partial\PhpNamespace;

final class ClassListGroup implements \IteratorAggregate
{
    /**
     * @param class-string[] $classes
     * @param ?non-empty-string $comment
     */
    public function __construct(
        private array $classes = [],
        private readonly ?string $comment = null,
        public readonly int $priority = 0,
    ) {}

    /**
     * @param class-string $class
     */
    public function hasClass(string $class): bool
    {
        return \in_array($class, $this->classes, true);
    }

    /**
     * @param class-string $bootloader
     */
    public function append(string $bootloader, ?string $afterClass = null): void
    {
        if ($afterClass === null) {
            $this->classes[] = $bootloader;
            return;
        }

        foreach ($this->classes as $pos => $value) {
            if ($afterClass === $value) {
                $this->classes = \array_merge(
                    \array_slice($this->classes, 0, (int) $pos + 1),
                    [$bootloader],
                    \array_slice($this->classes, (int) $pos + 1),
                );
                break;
            }
        }
    }

    /**
     * @param class-string $bootloader
     */
    public function prepend(string $bootloader, ?string $beforeClass = null): void
    {
        if ($beforeClass === null) {
            \array_unshift($this->classes, $bootloader);
            return;
        }

        foreach ($this->classes as $pos => $value) {
            if ($beforeClass === $value) {
                $this->classes = \array_merge(
                    \array_slice($this->classes, 0, (int) $pos),
                    [$bootloader],
                    \array_slice($this->classes, (int) $pos),
                );
                break;
            }
        }
    }

    public function render(PhpNamespace $namespace): string
    {
        $bootloaders = [];
        foreach ($this->classes as $bootloader) {
            $bootloaders[] = $namespace->simplifyName($bootloader) . '::class';
        }

        $comment = $this->comment !== null ? PHP_EOL . '// ' . $this->comment . PHP_EOL : PHP_EOL;

        return $comment . \implode(',' . PHP_EOL, $bootloaders);
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->classes as $class) {
            yield $class;
        }
    }
}
