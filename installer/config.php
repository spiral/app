<?php

declare(strict_types=1);

use Installer\Application;
use Installer\Package;
use Installer\Question;

return [
    new Application\Web(
        packages: [
            new Package\ExtMbString(),
            new Package\RoadRunnerBridge(),
            new Package\NyholmBridge(),
            new Package\SapiBridge(),
        ],
        questions: [
            new Question\CycleBridge(),
            new Question\CycleCollections(),
            new Question\Validator(),
            new Question\TemplateEngine(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\SentryBridge(),
        ],
        resources: [
            'common' => '',
            'applications/web/app' => 'app',
            'applications/web/public' => 'public',
            'applications/web/tests' => 'tests',
        ]
    ),
    new Application\Cli(
        questions: [
            new Question\CycleBridge(),
            new Question\RoadRunnerMetrics(),
        ],
        resources: [
            'common' => '',
        ]
    ),
    new Application\GRPC(
        packages: [
            new Package\ExtMbString(),
            new Package\ExtGRPC(),
            new Package\GRPC(),
            new Package\RoadRunnerBridge([]),
        ],
        questions: [
            new Question\CycleBridge(),
            new Question\Validator(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\SentryBridge(),
        ],
        resources: [
            'common' => '',
            'applications/grpc/app' => 'app',
            'applications/grpc/proto' => 'proto',
        ]
    ),
    new Application\CustomBuild(
        name: 'Custom build',
        questions: [
            new Question\ExtMbString(),
            new Question\Psr7Implementation(),
            new Question\Sapi(),
            new Question\RoadRunnerBridge(),
            new Question\CycleBridge(),
            new Question\CycleCollections(),
            new Question\RoadRunnerGRPC(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\TemplateEngine(),
            new Question\Validator(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\SentryBridge(),
        ],
        resources: [
            'common' => '',
        ]
    ),
];
