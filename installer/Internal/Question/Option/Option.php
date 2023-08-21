<?php

declare(strict_types=1);

namespace Installer\Internal\Question\Option;

use Installer\Application\ComposerPackages;
use Installer\Internal\Package;

final class Option extends AbstractOption
{
    /**
     * @var Package[]
     */
    private array $packages = [];

    /**
     * @param array<Package|ComposerPackages> $packages
     */
    public function __construct(
        string $name,
        array $packages = [],
    ) {
        parent::__construct($name);

        $this->setPackages($packages);
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @param array<Package|ComposerPackages> $packages
     */
    private function setPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->packages[] = $package instanceof ComposerPackages ? new Package($package) : $package;
        }
    }
}
