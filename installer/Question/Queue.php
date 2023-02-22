<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Component\Generator\Queue\Config;
use Installer\Component\Generator\Queue\Skeleton;
use Installer\Question\Option\BooleanOption;

final class Queue extends AbstractQuestion
{
    /**
     * @param BooleanOption[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Queue component?',
        bool $required = false,
        array $options = [
            new BooleanOption(name: 'Yes', generators: [
                new Config(),
                new Skeleton(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
