<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Kernel;

use Installer\Internal\EventStorage;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;

abstract class Bootloaders
{
    /**
     * @var ClassListGroup[]
     */
    protected array $groups = [];

    public function __construct(
        public readonly BootloaderPlaces $place,
        protected readonly ?EventStorage $eventStorage = null,
    ) {
    }

    /**
     * @param class-string[] $bootloaders
     * @param ?non-empty-string $comment
     */
    public function addGroup(array $bootloaders, ?string $comment = null, int $priority = 0): self
    {
        $this->groups[] = new ClassListGroup($bootloaders, $comment, $priority);

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
                if ($group->hasClass($afterBootloader)) {
                    $group->append($bootloader, $afterBootloader);
                    $found = true;
                }
            }
        }

        if ($found === false) {
            $this->groups[] = new ClassListGroup(classes: [$bootloader], priority: 100);
        }

        return $this;
    }

    /**
     * @param class-string $bootloader
     * @param class-string $beforeBootloader
     */
    public function prepend(string $bootloader, ?string $beforeBootloader = null): void
    {
        $founded = false;
        if ($beforeBootloader !== null) {
            foreach ($this->groups as $group) {
                if ($group->hasClass($beforeBootloader)) {
                    $group->prepend($bootloader, $beforeBootloader);
                    $founded = true;
                }
            }
        }

        if ($founded === false) {
            \array_unshift($this->groups, new ClassListGroup(classes: [$bootloader], priority: 100));
        }
    }

    abstract public function updateDeclaration(ClassDeclaration $class, PhpNamespace $namespace): void;
}
