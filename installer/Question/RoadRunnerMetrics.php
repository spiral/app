<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\RoadRunnerBridge\MetricsBootloader;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\OptionInterface;

final class RoadRunnerMetrics extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Do you need the RoadRunner Metrics?',
        bool $required = false,
        array $options = [
            new BooleanOption(
                name: 'Yes',
                generators: [
                    new MetricsBootloader()
                ]
            ),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
