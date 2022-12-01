<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Scheduler as Package;
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
                new Package(),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
