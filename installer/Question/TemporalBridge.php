<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\TemporalBridge as Package;
use Installer\Question\Option\Option;

final class TemporalBridge extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need the Temporal?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
