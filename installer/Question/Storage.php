<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Component\Generator\Storage\Config;
use Installer\Question\Option\BooleanOption;

final class Storage extends AbstractQuestion
{
    /**
     * @param BooleanOption[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Storage component?',
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
