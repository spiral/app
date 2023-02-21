<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\Queue\Config;
use Installer\Question\Option\BooleanOption;

final class Queue extends AbstractQuestion
{
    /**
     * @param BooleanOption[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Queued jobs?',
        bool $required = false,
        array $options = [
            new BooleanOption(name: 'Yes', generators: [
                new Config(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
