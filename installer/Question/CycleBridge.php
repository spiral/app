<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\CycleBridge as Package;
use Installer\Question\Option\Option;

final class CycleBridge extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need Cycle ORM?',
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
            default: $default
        );
    }
}
