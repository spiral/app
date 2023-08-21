<?php

declare(strict_types=1);

namespace Installer\Module\Serializers;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Internal\Question\Option\OptionInterface;
use Installer\Module\Serializers\LaravelSerializableClosure\Package as LaravelSerializableClosurePackage;
use Installer\Module\Serializers\SymfonySerializer\Package as SymfonySerializerPackage;

final class Question extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Which serializer component do you want to use?',
        bool $required = false,
        array $options = [
            new Option(name: 'Symfony Serializer', packages: [
                new SymfonySerializerPackage(),
            ]),
            new Option(name: 'Laravel Serializable Closure', packages: [
                new LaravelSerializableClosurePackage(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }

    public function getHelp(): ?string
    {
        return <<<'HELP'
        Serializer is a component that allows you to serialize and deserialize data structures. It is used by the
        Queue component to serialize messages.

        It can also be used to serialize objects for caching or other purposes.

        Spiral includes some basic serializers such as "json" and "php serializer" by default, but these may not always be
        sufficient for more complex use cases.

        If you need to serialize objects, you can use the Symfony Serializer component. https://symfony.com/doc/current/components/serializer.html
        If you need to serialize any complex data structures or closures, you can use the Laravel Serializable Closure component.
        HELP;
    }
}
