<?php

declare(strict_types=1);

use Installer\Application;
use Installer\Question;

return [
    new Application\Web(
        questions: [
            new Question\ApplicationSkeleton(),
            new Question\CycleBridge(default: 1),
            new Question\CycleCollections(default: 1),
            new Question\Validator(default: 1),
            new Question\Serializer(),
            new Question\Mailer(),
            new Question\TemplateEngine(default: 1),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\SentryBridge(),
        ]
    ),
    new Application\Cli(
        questions: [
            new Question\ApplicationSkeleton(),
            new Question\CycleBridge(),
            new Question\Serializer(),
            new Question\Mailer(),
            new Question\RoadRunnerBridge(),
            new Question\RoadRunnerMetrics(),
        ]
    ),
    new Application\GRPC(
        questions: [
            new Question\CycleBridge(),
            new Question\Validator(),
            new Question\Serializer(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\Mailer(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\SentryBridge(),
        ],
    ),
    /*
    new Application\CustomBuild(
        questions: [
            new Question\ApplicationSkeleton(),
            new Question\ExtMbString(),
            new Question\Psr7Implementation(),
            new Question\Sapi(),
            new Question\RoadRunnerBridge(),
            new Question\CycleBridge(),
            new Question\Mailer(),
            new Question\CycleCollections(),
            new Question\RoadRunnerGRPC(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\TemplateEngine(),
            new Question\Validator(),
            new Question\Serializer(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\SentryBridge(),
        ],
    ),
    */
];
