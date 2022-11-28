<?php

declare(strict_types=1);

namespace Installer\Question\Option;

use Installer\Package\Package;
use Installer\Package\Packages;

final class Option
{
    /**
     * @var Package[]
     */
    private array $packages = [];

    /**
     * @param array<Package|Packages> $packages
     */
    public function __construct(
        private readonly string $name,
        array $packages = []
    ) {
        $this->setPackages($packages);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @param array<Package|Packages> $packages
     */
    private function setPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->packages[] = $package instanceof Packages ? new Package($package) : $package;
        }
    }
}
