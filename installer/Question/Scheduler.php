<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\Scheduler\Bootloaders;
use Installer\Package\Package;
use Installer\Package\Packages;
use Installer\Question\Option\Option;

final class Scheduler extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need a cron jobs Scheduler?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(
                    package: Packages::Scheduler,
                    generators: [
                        new Bootloaders()
                    ]
                )
            ])
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
