<?php

declare(strict_types=1);

namespace Installer\Module\Validators;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\Validators\Laravel\Package as LaravelValidator;
use Installer\Module\Validators\Spiral\Package as SpiralValidator;
use Installer\Module\Validators\Symfony\Package as SymfonyValidator;

final class Question extends AbstractQuestion
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
        int $default = self::NONE_OPTION,
    ) {
        parent::__construct(
            question: $question,
            required: $required,
            options: $options,
            default: $default,
        );
    }

    public function getHelp(): ?string
    {
        return <<<'HELP'
        The validation component of Spiral allows you to validate data that is submitted by a user or received from an external source.
        There are three validator bridges available for use with Spiral validation component:

        Spiral Validator - This is the default validator bridge. It is a simple, lightweight validator that can handle basic validation tasks. https://spiral.dev/docs/validation-spiral
        Symfony Validator - This validator bridge provides integration with the Symfony Validator component, which is a more powerful and feature-rich validation library. https://spiral.dev/docs/validation-symfony
        Laravel Validator - This validator bridge provides integration with the Laravel Validator, which is a validation component used in the Laravel framework. https://spiral.dev/docs/validation-laravel

        Documentation: https://spiral.dev/docs/validation-factory
        HELP;
    }
}
