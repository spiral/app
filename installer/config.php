<?php

declare(strict_types=1);

use Installer\Application;
use Installer\Question;

return [
    new Application\Web(
        questions: [
            new Question\ApplicationSkeleton(),
            new Question\Sapi(),
            new Question\CycleBridge(default: 1),
            new Question\CycleCollections(default: 1),
            new Question\Validator(default: 1),
            //new Question\Serializer(),
            new Question\Queue(),
            new Question\Cache(),
            new Question\Mailer(),
            new Question\Storage(),
            new Question\TemplateEngine(default: 1),
            new Question\EventDispatcher(),
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
            //new Question\Serializer(),
            new Question\Queue(),
            new Question\Cache(),
            new Question\Mailer(),
            new Question\Storage(),
            new Question\RoadRunnerBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\TemporalBridge(),
        ]
    ),
    new Application\GRPC(
        questions: [
            new Question\CycleBridge(),
            new Question\Validator(),
            //new Question\Serializer(),
            new Question\EventDispatcher(),
            new Question\Queue(),
            new Question\Cache(),
            new Question\Mailer(),
            new Question\Storage(),
            new Question\Scheduler(),
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
            new Question\Queue(),
            new Question\Cache(),
            new Question\Mailer(),
            new Question\Storage(),
            new Question\CycleCollections(),
            new Question\RoadRunnerGRPC(),
            new Question\TemporalBridge(),
            new Question\RoadRunnerMetrics(),
            new Question\TemplateEngine(),
            new Question\Validator(),
            //new Question\Serializer(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\SentryBridge(),
        ],
    ),
    */
];
