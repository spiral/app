<?php

declare(strict_types=1);

namespace Installer\Module\TemplateEngines;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\TemplateEngines\PlainPHP\Package as PlainPHPPackage;
use Installer\Module\TemplateEngines\Stempler\Package as StemplerPackage;
use Installer\Module\TemplateEngines\Twig\Package as TwigPackage;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Which template engine do you want to use?',
        bool $required = true,
        array $options = [
            new Option(
                name: 'Stempler',
                packages: [
                    new StemplerPackage(),
                ]
            ),
            new Option(
                name: 'Twig',
                packages: [
                    new TwigPackage(),
                ]
            ),
            new Option(
                name: 'Plain PHP',
                packages: [
                    new PlainPHPPackage(),
                ]
            ),
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
