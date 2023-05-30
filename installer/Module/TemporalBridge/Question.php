<?php

declare(strict_types=1);

namespace Installer\Module\TemporalBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;

final class Question extends AbstractQuestion
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
        ],
        array $depends = [
            ComposerPackages::RoadRunnerBridge,
        ]
    ) {
        parent::__construct($question, $required, $options, $depends);
    }
}
