<?php

declare(strict_types=1);

namespace Installer\Application;

enum ComposerPackages: string
{
    case YiiErrorHandler = 'spiral-packages/yii-error-handler-bridge:^1.1';
    case ExtMbString = 'ext-mbstring:*';
    case ExtGRPC = 'ext-grpc:*';
    case ExtSockets = 'ext-sockets:*';
    case GRPC = 'grpc/grpc:^1.42';
    case Dumper = 'dev:spiral/dumper:^3.0';
    case RoadRunnerBridge = 'spiral/roadrunner-bridge:^3.0';
    case RoadRunnerCli = 'spiral/roadrunner-cli:^2.4';
    case NyholmBridge = 'spiral/nyholm-bridge:^1.3';
    case SapiBridge = 'spiral/sapi-bridge:^1.0.1';
    case CycleBridge = 'spiral/cycle-bridge:^2.4';
    case DoctrineCollections = 'doctrine/collections:^1.8';
    case LoophpCollections = 'loophp/collection:^6.0';
    case IlluminateCollections = 'illuminate/collections:^9.0';
    case RoadRunnerGRPC = 'spiral/roadrunner-grpc:^3.0';
    case SpiralValidator = 'spiral/validator:^1.3';
    case SymfonyValidator = 'spiral-packages/symfony-validator:^1.3';
    case LaravelValidator = 'spiral-packages/laravel-validator:^1.1';
    case Scheduler = 'spiral-packages/scheduler:^2.1';
    case TemporalBridge = 'spiral/temporal-bridge:^2.1';
    case SentryBridge = 'spiral/sentry-bridge:^2.0';
    case StemplerBridge = 'spiral/stempler-bridge:^3.2';
    case TwigBridge = 'spiral/twig-bridge:^2.0';
    case LeagueEvent = 'spiral-packages/league-event:^1.0';
    case SymfonySerializer = 'spiral-packages/symfony-serializer:^2.0';
    case LaravelSerializableClosure = 'spiral-packages/serializable-closure:^1.0';
}
