<?php

declare(strict_types=1);

use Installer\Application;
use Installer\Question;

return [
    new Application\Web(
        questions: [
            new Question\CycleBridge(),
            new Question\CycleCollections(),
            new Question\Validator(),
            new Question\TemplateEngine(),
            new Question\EventBus(),
            new Question\Scheduler(),
            new Question\TemporalBridge(),
            new Question\SentryBridge(),
        ],
        resources: [
            'applications/web/app.php' => 'app.php',
            'applications/web/deptrac.yaml' => 'deptrac.yaml',
            'applications/web/app' => 'app',
            'applications/web/public' => 'public',
            'applications/web/tests' => 'tests',
        ]
    ),
    new Application\Cli(questions: [
        new Question\CycleBridge(),
    ]),
    new Application\GRPC(),
    new Application\CustomBuild(questions: [
        new Question\ExtMbString(),
        new Question\Psr7Implementation(),
        new Question\Sapi(),
        new Question\RoadRunnerBridge(),
        new Question\RoadRunnerGRPC(),
        new Question\CycleBridge(),
        new Question\CycleCollections(),
        new Question\TemporalBridge(),
        new Question\TemplateEngine(),
        new Question\Validator(),
        new Question\EventBus(),
        new Question\Scheduler(),
        new Question\SentryBridge(),
    ]),
];
