<?php

declare(strict_types=1);

namespace Installer\Application;

enum ComposerPackages: string
{
    case YiiErrorHandler = 'spiral-packages/yii-error-handler-bridge:^1.1';
    case ExtMbString = 'ext-mbstring:*';
    case ExtGRPC = 'ext-grpc:*';
    case ExtSockets = 'ext-sockets:*';
    case ExtProtobuf = 'ext-protobuf:*';
    case GRPC = 'grpc/grpc:^1.42';
    case Dumper = 'dev:spiral/dumper:^3.3.1';
    case Dload = 'dev:internal/dload:^1.0.2';
    case RoadRunnerBridge = 'spiral/roadrunner-bridge:^4.0';
    case RoadRunnerCli = 'spiral/roadrunner-cli:^2.5';
    case NyholmBridge = 'spiral/nyholm-bridge:^1.3';
    case SapiBridge = 'spiral/sapi-bridge:^1.1';
    case CycleBridge = 'spiral/cycle-bridge:^2.11';
    case DoctrineCollections = 'doctrine/collections:^2.3';
    case LoophpCollections = 'loophp/collection:^7.6';
    case IlluminateCollections = 'illuminate/collections:8 - 12';
    case RoadRunnerGRPC = 'spiral/roadrunner-grpc:^3.5';
    case SpiralValidator = 'spiral/validator:^1.5';
    case SymfonyValidator = 'spiral-packages/symfony-validator:^1.5';
    case LaravelValidator = 'spiral-packages/laravel-validator:^1.2';
    case Scheduler = 'spiral-packages/scheduler:^2.1';
    case TemporalBridge = 'spiral/temporal-bridge:^3.3';
    case SentryBridge = 'spiral/sentry-bridge:^2.3';
    case StemplerBridge = 'spiral/stempler-bridge:^3.15';
    case TwigBridge = 'spiral/twig-bridge:^2.0.1';
    case LeagueEvent = 'spiral-packages/league-event:^1.0.1';
    case SymfonySerializer = 'spiral-packages/symfony-serializer:^2.0.1';
    case LaravelSerializableClosure = 'spiral-packages/serializable-closure:^1.0';
    case DataGridBridge = 'spiral/data-grid-bridge:^3.0.1';
    case Translator = 'spiral/translator:^3.15';
    case Views = 'spiral/views:^3.15';
    case Http = 'spiral/http:^3.15';
}
