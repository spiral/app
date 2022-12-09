<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\LaravelSerializableClosure;
use Installer\Package\SymfonySerializer;
use Installer\Question\Option\Option;
use Installer\Question\Option\OptionInterface;

final class Serializer extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Which serializer component do you want to use?',
        bool $required = false,
        array $options = [
            new Option(name: 'Symfony Serializer', packages: [
                new SymfonySerializer(),
            ]),
            new Option(name: 'Laravel Serializable Closure', packages: [
                new LaravelSerializableClosure(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
