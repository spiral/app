<?php

declare(strict_types=1);

namespace Installer\Application;

use App\Application\Kernel;
use Composer\Package\PackageInterface;
use Installer\Package\Packages;
use Installer\Question\QuestionInterface;

/**
 * @psalm-import-type AutoloadRules from PackageInterface
 * @psalm-import-type DevAutoloadRules from PackageInterface
 */
final class Web extends AbstractApplication
{
    /**
     * @param Packages[] $packages
     * @param AutoloadRules $autoload
     * @param DevAutoloadRules $autoloadDev
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        string $name = 'Web',
        array $packages = [
            Packages::ExtMbString,
            Packages::RoadRunnerBridge,
            Packages::NyholmBridge,
            Packages::SapiBridge,
        ],
        array $autoload = [
            'psr-4' => [
                'App\\' => 'app/src',
            ],
            'files' => [
                'app/src/Application/helpers.php',
            ],
        ],
        array $autoloadDev = [
            'psr-4' => [
                'Tests\\' => 'tests',
            ],
        ],
        array $questions = [],
        array $resources = [],
    ) {
        parent::__construct($name, $packages, $autoload, $autoloadDev, $questions, $resources);
    }

    public function getKernelClass(): string
    {
        /** @psalm-suppress UndefinedClass */
        return Kernel::class;
    }
}
