<?php

declare(strict_types=1);

namespace Installer\Module\SentryBridge;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\SentryBridge\Package as Package;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need the Sentry?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
