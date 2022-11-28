<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\TemporalBridge\Bootloaders;
use Installer\Package\Package;
use Installer\Package\Packages;
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
                new Package(
                    package: Packages::TemporalBridge,
                    generators: [new Bootloaders()]
                ),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
