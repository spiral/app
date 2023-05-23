<?php

declare(strict_types=1);

namespace Installer\Module\CycleBridge;

use Installer\Application\ComposerPackages;
use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;

final class CollectionsQuestion extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Which Collections do you want to use with Cycle ORM?',
        bool $required = false,
        array $options = [
            new Option(name: 'Doctrine Collections', packages: [
                ComposerPackages::DoctrineCollections,
            ]),
            new Option(name: 'Laravel Collections', packages: [
                ComposerPackages::IlluminateCollections,
            ]),
            new Option(name: 'Loophp Collections', packages: [
                ComposerPackages::LoophpCollections,
            ]),
            new Option(name: 'Use PHP array as the collection'),
        ],
        array $conditions = [
            'require' => [
                ComposerPackages::CycleBridge,
            ],
        ],
        int $default = self::NONE_OPTION
    ) {
        parent::__construct(
            question: $question,
            required: $required,
            options: $options,
            conditions: $conditions,
            default: $default
        );
    }
}
