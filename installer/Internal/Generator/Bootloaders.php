<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

use Nette\PhpGenerator\Literal;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;

final class Bootloaders
{
    /**
     * @var BootloaderGroup[]
     */
    private array $groups = [];

    public function __construct(
        private readonly BootloaderPlaces $place
    ) {
    }

    /**
     * @param class-string[] $bootloaders
     * @param ?non-empty-string $comment
     */
    public function addGroup(array $bootloaders, ?string $comment = null, int $priority = 0): self
    {
        $this->groups[] = new BootloaderGroup($bootloaders, $comment, $priority);

        return $this;
    }

    /**
     * @param class-string $bootloader
     * @param class-string|null $afterBootloader
     */
    public function append(string $bootloader, ?string $afterBootloader = null): self
    {
        $found = false;

        if ($afterBootloader !== null) {
            foreach ($this->groups as $group) {
                if ($group->hasBootloader($afterBootloader)) {
                    $group->append($bootloader, $afterBootloader);
                    $found = true;
                }
            }
        }

        if ($found === false) {
            $this->groups[] = new BootloaderGroup(bootloaders: [$bootloader], priority: 100);
        }

        return $this;
    }

    /**
     * @param class-string $bootloader
     * @param class-string $beforeBootloader
     */
    public function prepend(string $bootloader, string $beforeBootloader): void
    {
        $founded = false;
        foreach ($this->groups as $group) {
            if ($group->hasBootloader($beforeBootloader)) {
                $group->prepend($bootloader, $beforeBootloader);
                $founded = true;
            }
        }

        if ($founded === false) {
            $this->groups[] = new BootloaderGroup(bootloaders: [$bootloader], priority: 100);
        }
    }

    public function updateDeclaration(ClassDeclaration $class, PhpNamespace $namespace): void
    {
        \uasort(
            $this->groups,
            static fn (BootloaderGroup $a, BootloaderGroup $b) => $a->priority <=> $b->priority
        );

        $groups = \array_map(
            static fn (BootloaderGroup $group) => $group->render($namespace),
            \array_values($this->groups)
        );

        $class
            ->getConstant($this->place->value)
            ->setValue([new Literal(\implode(',' . PHP_EOL, $groups))]);
    }
}
