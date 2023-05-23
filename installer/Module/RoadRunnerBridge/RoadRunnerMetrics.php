<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\OptionInterface;
use Installer\Module\RoadRunnerBridge\Metrics\Generator\MetricsBootloader;

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
                    new MetricsBootloader(),
                ]
            ),
        ],
        array $conditions = [
            'require' => [
                ComposerPackages::RoadRunnerBridge,
            ],
        ]
    ) {
        parent::__construct($question, $required, $options, $conditions);
    }
}
