<?php

declare(strict_types=1);

namespace Installer\Module\Queue;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Module\Queue\Generator\Config;
use Installer\Module\Queue\Generator\Skeleton;

final class Question extends AbstractQuestion
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
