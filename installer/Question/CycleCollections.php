<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Packages;
use Installer\Question\Option\Option;

final class CycleCollections extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Which Collections do you want to use with Cycle ORM?',
        bool $required = false,
        array $options = [
            new Option(name: 'Doctrine Collections', packages: [
                Packages::DoctrineCollections,
            ]),
            new Option(name: 'Laravel Collections', packages: [
                Packages::IlluminateCollections,
            ]),
            new Option(name: 'Use PHP array as the collection'),
        ],
        array $conditions = [
            'require' => [
                Packages::CycleBridge,
            ],
        ]
    ) {
        parent::__construct($question, $required, $options, $conditions);
    }
}
