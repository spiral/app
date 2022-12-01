<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\SapiBridge;
use Installer\Question\Option\Option;

final class Sapi extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need the SAPI?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new SapiBridge(),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
