<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\NyholmBridge;
use Installer\Question\Option\Option;

final class Psr7Implementation extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need PSR-7 implementation?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new NyholmBridge(),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
