<?php

declare(strict_types=1);

namespace Installer\Application;

use App\Kernel;
use Composer\Package\PackageInterface;
use Installer\Package\Packages;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
final class Cli extends AbstractApplication
{
    /**
     * @param Packages[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        string $name = 'Cli',
        array $packages = [],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
            ],
        ],
        array $autoloadDev = [],
        array $questions = [],
        array $resources = []
    ) {
        parent::__construct($name, $packages, $autoload, $autoloadDev, $questions, $resources);
    }

    public function getKernelClass(): string
    {
        /** @psalm-suppress UndefinedClass */
        return Kernel::class;
    }
}
