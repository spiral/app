<?php

declare(strict_types=1);

namespace Installer\Module\Scheduler;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\Scheduler\Package as Package;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need a cron jobs scheduler?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
