<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\OptionInterface;

final class ApplicationSkeleton extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Do you want to add a default application skeleton?',
        bool $required = false,
        array $options = [
            new BooleanOption(name: 'Yes'),
        ],
        int $default = 1
    ) {
        parent::__construct(
            question: $question,
            required: $required,
            options: $options,
            default: $default
        );
    }
}
