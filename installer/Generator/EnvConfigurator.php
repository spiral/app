<?php

declare(strict_types=1);

namespace Installer\Generator;

use Installer\Resource;
use Spiral\Files\Files;
use Spiral\Files\FilesInterface;

final class EnvConfigurator
{
    private const FILENAME = '.env.sample';

    public function __construct(
        private readonly string $projectRoot,
        private readonly Resource $resource
    ) {
        $this->addRequiredValues();
    }

    /**
     * @var EnvGroup[]
     */
    private array $groups = [];

    /**
     * @param array<non-empty-string, mixed> $values
     * @param ?non-empty-string $comment
     */
    public function addGroup(array $values, ?string $comment = null, int $priority = 0): void
    {
        $this->groups[] = new EnvGroup($values, $comment, $priority);
    }

    /**
     * @param non-empty-string $key
     */
    public function addValue(string $key, mixed $value): void
    {
        $founded = false;
        foreach ($this->groups as $group) {
            if ($group->hasValue($key)) {
                $group->addValue($key, $value);
                $founded = true;
                break;
            }
        }

        if (!$founded) {
            $this->addGroup(values: [$key => $value], priority: 100);
        }
    }

    public function __destruct()
    {
        \uasort($this->groups, static fn (EnvGroup $a, EnvGroup $b) => $a->priority <=> $b->priority);

        $groups = \array_map(
            static fn (EnvGroup $group) => $group->render(),
            \array_values($this->groups)
        );

        (new Files())->write(
            $this->projectRoot . self::FILENAME,
            \implode(PHP_EOL, $groups),
            FilesInterface::RUNTIME
        );

        $this->resource->createEnv();
    }

    private function addRequiredValues(): void
    {
        $this->addGroup(
            values: ['APP_ENV' => 'local'],
            comment: 'Environment (prod or local)',
            priority: 1
        );
        $this->addGroup(
            values: ['DEBUG' => true],
            comment: 'Debug mode set to TRUE disables view caching and enables higher verbosity',
            priority: 2
        );
        $this->addGroup(
            values: ['ENCRYPTER_KEY' => '{encrypt-key}'],
            comment: 'Set to an application specific value, used to encrypt/decrypt cookies etc',
            priority: 3
        );
        $this->addGroup(
            values: [
                'MONOLOG_DEFAULT_CHANNEL' => 'default',
                'MONOLOG_DEFAULT_LEVEL' => 'DEBUG # DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY'
            ],
            comment: 'Monolog',
            priority: 4
        );
        $this->addGroup(
            values: [
                'QUEUE_CONNECTION' => 'sync',
            ],
            comment: 'Queue',
            priority: 5
        );
        $this->addGroup(
            values: [
                'CACHE_STORAGE' => 'local',
            ],
            comment: 'Cache',
            priority: 6
        );
        $this->addGroup(
            values: [
                'STORAGE_DEFAULT' => 'default',
            ],
            comment: 'Storage',
            priority: 7
        );
    }
}
