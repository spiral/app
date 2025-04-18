<?php

declare(strict_types=1);

namespace Installer\Module\DataGridBridge;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need Data Grid?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(),
            ]),
        ],
        int $default = self::NONE_OPTION,
    ) {
        parent::__construct(
            question: $question,
            required: $required,
            options: $options,
            default: $default,
        );
    }
}
