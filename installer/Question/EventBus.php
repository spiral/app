<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\LeagueEvent\Bootloaders;
use Installer\Package\Package;
use Installer\Package\Packages;
use Installer\Question\Option\Option;

final class EventBus extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you want to use the Event Bus?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new Package(
                    package: Packages::LeagueEvent,
                    generators: [
                        new Bootloaders()
                    ]
                )
            ])
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
