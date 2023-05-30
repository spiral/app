<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\Option;
use Installer\Module\RoadRunnerBridge\GRPC\Generator\GRPCBootloader;

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
        array $depends = [
            ComposerPackages::RoadRunnerBridge,
        ]
    ) {
        parent::__construct($question, $required, $options, $depends);
    }
}
