<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\RoadRunnerBridge\GRPCBootloader;
use Installer\Package\Packages;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\Option;

final class RoadRunnerGRPC extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need the RoadRunner gRPC?',
        bool $required = false,
        array $options = [
            new BooleanOption(
                name: 'Yes',
                generators: [
                    new GRPCBootloader(),
                ]
            ),
        ],
        array $conditions = [
            'require' => [
                Packages::RoadRunnerBridge,
            ],
        ]
    ) {
        parent::__construct($question, $required, $options, $conditions);
    }
}
