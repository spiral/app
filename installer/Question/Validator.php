<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\LaravelValidator;
use Installer\Package\SpiralValidator;
use Installer\Package\SymfonyValidator;
use Installer\Question\Option\Option;

final class Validator extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Which validator component do you want to use?',
        bool $required = false,
        array $options = [
            new Option(name: 'Spiral Validator', packages: [
                new SpiralValidator(),
            ]),
            new Option(name: 'Symfony Validator', packages: [
                new SymfonyValidator(),
            ]),
            new Option(name: 'Laravel Validator', packages: [
                new LaravelValidator(),
            ]),
        ],
        int $default = self::NONE_OPTION
    ) {
        parent::__construct(
            question: $question,
            required: $required,
            options: $options,
            default: $default
        );
    }
}
