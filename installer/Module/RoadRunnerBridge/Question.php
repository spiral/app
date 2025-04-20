<?php

declare(strict_types=1);

namespace Installer\Module\RoadRunnerBridge;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\RoadRunnerBridge\Common\Package as RoadRunnerBridgeCommonPackage;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need RoadRunner?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new RoadRunnerBridgeCommonPackage(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
