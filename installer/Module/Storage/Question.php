<?php

declare(strict_types=1);

namespace Installer\Module\Storage;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Module\Storage\Generator\Config;

final class Question extends AbstractQuestion
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
