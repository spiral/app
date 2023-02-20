<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Packages;
use Installer\Question\Option\Option;

final class ExtMbString extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Add `ext-mbstring` to the composer.json?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                Packages::ExtMbString,
            ]),
        ],
        int $default = self::NONE_OPTION,
    ) {
        parent::__construct($question, $required, $options, default: $default);
    }
}
