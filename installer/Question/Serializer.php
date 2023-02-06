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
