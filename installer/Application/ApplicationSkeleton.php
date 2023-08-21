<?php

declare(strict_types=1);

namespace Installer\Application;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\OptionInterface;

final class ApplicationSkeleton extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Create a default application structure and demo data?',
        bool $required = false,
        array $options = [
            new BooleanOption(name: 'Yes'),
        ],
        int $default = self::YES_OPTION,
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
        Selecting this option will create a default application structure and demo data for your project. This includes a home
        controller, an example console command, an example queue job, views, and locales. The demo data is intended to provide
        you with a quick starting point for your application and can be modified or removed as needed.
        HELP;
    }
}
