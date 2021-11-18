<?php

declare(strict_types=1);

namespace App\Bootloader;

use Cycle\Migrations\Config\MigrationConfig;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\Migrator;
use Cycle\Migrations\RepositoryInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Bootloader\TokenizerBootloader;
use Spiral\Config\ConfiguratorInterface;

final class MigrationsBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        TokenizerBootloader::class,
        DatabaseBootloader::class,
    ];

    protected const SINGLETONS = [
        Migrator::class => Migrator::class,
        RepositoryInterface::class => FileRepository::class,
    ];

    public function boot(
        ConfiguratorInterface $config,
        EnvironmentInterface $env,
        DirectoriesInterface $dirs
    ): void {
        if (!$dirs->has('migrations')) {
            $dirs->set('migrations', $dirs->get('app').'migrations');
        }

        $config->setDefaults(
            'migration',
            [
                'directory' => $dirs->get('migrations'),
                'table' => 'migrations',
                'safe' => $env->get('SAFE_MIGRATIONS', false),
            ]
        );
    }
}
