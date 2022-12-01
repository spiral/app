<?php

declare(strict_types=1);

namespace Installer\Generator;

use Spiral\Reactor\Partial\PhpNamespace;

final class BootloaderGroup
{
    /**
     * @param class-string[] $bootloaders
     * @param ?non-empty-string $comment
     */
    public function __construct(
        private array $bootloaders = [],
        private readonly ?string $comment = null,
        public readonly int $priority = 0
    ) {
    }

    /**
     * @param class-string $bootloader
     */
    public function hasBootloader(string $bootloader): bool
    {
        return \in_array($bootloader, $this->bootloaders, true);
    }

    /**
     * @param class-string $bootloader
     */
    public function append(string $bootloader, string $afterBootloader): void
    {
        foreach ($this->bootloaders as $pos => $value) {
            if ($afterBootloader === $value) {
                $this->bootloaders = \array_merge(
                    \array_slice($this->bootloaders, 0, (int) $pos + 1),
                    [$bootloader],
                    \array_slice($this->bootloaders, (int) $pos + 1)
                );
                break;
            }
        }
    }

    /**
     * @param class-string $bootloader
     */
    public function prepend(string $bootloader, string $beforeBootloader): void
    {
        foreach ($this->bootloaders as $pos => $value) {
            if ($beforeBootloader === $value) {
                $this->bootloaders = \array_merge(
                    \array_slice($this->bootloaders, 0, (int) $pos),
                    [$bootloader],
                    \array_slice($this->bootloaders, (int) $pos)
                );
                break;
            }
        }
    }

    public function render(PhpNamespace $namespace): string
    {
        $bootloaders = [];
        foreach ($this->bootloaders as $bootloader) {
            $bootloaders[] = $namespace->simplifyName($bootloader) . '::class';
        }

        $comment = $this->comment !== null ? PHP_EOL . '// ' . $this->comment . PHP_EOL : PHP_EOL;

        return $comment . \implode(',' . PHP_EOL, $bootloaders);
    }
}
