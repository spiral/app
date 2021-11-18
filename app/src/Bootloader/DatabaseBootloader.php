<?php

declare(strict_types=1);

namespace App\Bootloader;

use Cycle\Database\Database;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Cycle\Database\DatabaseProviderInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container\SingletonInterface;

final class DatabaseBootloader extends Bootloader implements SingletonInterface
{
    protected const SINGLETONS = [
        DatabaseProviderInterface::class => DatabaseManager::class,
    ];

    protected const BINDINGS = [
        DatabaseInterface::class => Database::class,
    ];

    private ConfiguratorInterface $config;

    public function __construct(ConfiguratorInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Init database config.
     */
    public function boot(): void
    {
        $this->config->setDefaults(
            'database',
            [
                'default' => 'default',
                'aliases' => [],
                'databases' => [],
                'drivers' => [],
            ]
        );
    }
}

