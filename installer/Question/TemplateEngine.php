<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\StemplerBridge;
use Installer\Package\TwigBridge;
use Installer\Question\Option\Option;

final class TemplateEngine extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Which template engine do you want to use?',
        bool $required = false,
        array $options = [
            new Option(
                name: 'Stempler',
                packages: [
                    new StemplerBridge(),
                ]
            ),
            new Option(
                name: 'Twig',
                packages: [
                    new TwigBridge(),
                ]
            ),
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
