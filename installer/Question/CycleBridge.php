<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\CycleBridge\Bootloaders;
use Installer\Package\Generator\CycleBridge\Config;
use Installer\Package\Package;
use Installer\Package\Packages;
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
                new Package(
                    package: Packages::CycleBridge,
                    resources: [
                        'packages/cycle/config' => 'app/config',
                    ],
                    generators: [
                        new Bootloaders(),
                        new Config(),
                    ]
                ),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
