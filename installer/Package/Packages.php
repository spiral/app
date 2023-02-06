<?php

declare(strict_types=1);

namespace Installer\Package;

enum Packages: string
{
    case ExtMbString = 'ext-mbstring:*';
    case ExtGRPC = 'ext-grpc:*';
    case GRPC = 'grpc/grpc:^1.42';
    case RoadRunnerBridge = 'spiral/roadrunner-bridge:^2.1';
    case NyholmBridge = 'spiral/nyholm-bridge:^1.3';
    case SapiBridge = 'spiral/sapi-bridge:^1.0.1';
    case CycleBridge = 'spiral/cycle-bridge:^2.1';
    case DoctrineCollections = 'doctrine/collections:^1.8';
    case LoophpCollections = 'loophp/collection:^6.0';
    case IlluminateCollections = 'illuminate/collections:^9.0';
    case RoadRunnerGRPC = 'spiral/roadrunner-grpc:^2.0';
    case SpiralValidator = 'spiral/validator:^1.1';
    case SymfonyValidator = 'spiral-packages/symfony-validator:^1.2';
    case LaravelValidator = 'spiral-packages/laravel-validator:^1.1';
    case Scheduler = 'spiral-packages/scheduler:^2.1';
    case TemporalBridge = 'spiral/temporal-bridge:^2.1';
    case SentryBridge = 'spiral/sentry-bridge:^2.0';
    case StemplerBridge = 'spiral/stempler-bridge:^3.2';
    case TwigBridge = 'spiral/twig-bridge:^2.0';
    case LeagueEvent = 'spiral-packages/league-event:^1.0';
    case SymfonySerializer = 'spiral-packages/symfony-serializer:^1.0';
    case LaravelSerializableClosure = 'spiral-packages/serializable-closure:^1.0';
}
