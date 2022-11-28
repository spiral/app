<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\StemplerBridge\Bootloaders as StemplerBootloaders;
use Installer\Package\Generator\TwigBridge\Bootloaders as TwigBootloaders;
use Installer\Package\Package;
use Installer\Package\Packages;
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
                    new Package(
                        package: Packages::StemplerBridge,
                        resources: ['packages/stempler/views' => 'app/views'],
                        generators: [new StemplerBootloaders()]
                    ),
                ]
            ),
            new Option(
                name: 'Twig',
                packages: [
                    new Package(
                        package: Packages::TwigBridge,
                        resources: ['packages/twig/views' => 'app/views'],
                        generators: [new TwigBootloaders()]
                    ),
                ]
            ),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
