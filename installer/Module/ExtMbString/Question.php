<?php

declare(strict_types=1);

namespace Installer\Module\ExtMbString;

use Installer\Application\ComposerPackages;
use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Add `ext-mbstring` to the composer.json?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                ComposerPackages::ExtMbString,
            ]),
        ],
        int $default = self::NONE_OPTION,
    ) {
        parent::__construct($question, $required, $options, default: $default);
    }
}
