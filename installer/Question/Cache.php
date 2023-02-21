<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Component\Generator\Cache\Config;
use Installer\Question\Option\BooleanOption;

final class Cache extends AbstractQuestion
{
    /**
     * @param BooleanOption[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Cache component?',
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