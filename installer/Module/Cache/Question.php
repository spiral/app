<?php

declare(strict_types=1);

namespace Installer\Module\Cache;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Module\Cache\Generator\Config;

final class Question extends AbstractQuestion
{
    /**
     * @param BooleanOption[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Cache component?',
        bool $required = false,
        array $options = [
            new BooleanOption(
                name: 'Yes',
                generators: [
                    new Config(),
                ]
            ),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
