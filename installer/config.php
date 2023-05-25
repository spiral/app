<?php

declare(strict_types=1);

use Installer\Application;
use Installer\Module;

return [
    'app' => [
        new Application\Web\Application(
            questions: [
                new Application\ApplicationSkeleton(),
                new Module\SapiBridge\Question(),
                new Module\CycleBridge\Question(default: 1),
                new Module\CycleBridge\CollectionsQuestion(default: 1),
                new Module\Validators\Question(default: 1),
                //new Question\Serializer(),
                new Module\Queue\Question(),
                new Module\Cache\Question(),
                new Module\Mailer\Question(),
                new Module\Storage\Question(),
                new Module\TemplateEngines\Question(default: 1),
                new Module\EventDispatcher\Question(),
                new Module\Scheduler\Question(),
                new Module\TemporalBridge\Question(),
                new Module\RoadRunnerBridge\RoadRunnerMetrics(),
                new Module\SentryBridge\Question(),
            ]
        ),
        new Application\Cli\Application(
            questions: [
                new Application\ApplicationSkeleton(),
                new Module\CycleBridge\Question(),
                //new Module\Serializers\Question(),
                new Module\Queue\Question(),
                new Module\Cache\Question(),
                new Module\Mailer\Question(),
                new Module\Storage\Question(),
                new Module\RoadRunnerBridge\Question(),
                new Module\RoadRunnerBridge\RoadRunnerMetrics(),
                new Module\TemporalBridge\Question(),
            ]
        ),
        new Application\GRPC\Application(
            questions: [
                new Application\ApplicationSkeleton(),
                new Module\CycleBridge\Question(),
                new Module\Validators\Question(),
                //new Module\Serializers\Question(),
                new Module\EventDispatcher\Question(),
                new Module\Queue\Question(),
                new Module\Cache\Question(),
                new Module\Mailer\Question(),
                new Module\Storage\Question(),
                new Module\Scheduler\Question(),
                new Module\TemporalBridge\Question(),
                new Module\RoadRunnerBridge\RoadRunnerMetrics(),
                new Module\SentryBridge\Question(),
            ],
        ),
        /*
        new Application\CustomBuild(
            questions: [
                new Question\ApplicationSkeleton(),
                new Question\ExtMbString(),
                new Question\Psr7Implementation(),
                new Module\SapiBridge\Question(),
                new Question\RoadRunnerBridge(),
                new Module\CycleBridge\Question(),
                new Module\Queue\Question(),
                new Module\Cache\Question(),
                new Module\Mailer\Question(),
                new Module\Storage\Question(),
                new Question\CycleCollections(),
                new Question\RoadRunnerGRPC(),
                new Question\TemporalBridge(),
                new Question\RoadRunnerMetrics(),
                new Question\TemplateEngine(),
                new Question\Validator(),
                //new Module\Serializers\Question(),
                new Question\EventBus(),
                new Question\Scheduler(),
                new Module\SentryBridge\Question(),
            ],
        ),
        */
    ],
    'directories' => [
        ':common:' => __DIR__ .'/Application/Common/resources'
    ],
];
