<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\SpiralValidator\Bootloaders as SpiralValidatorBootloaders;
use Installer\Package\Generator\SymfonyValidator\Bootloaders as SymfonyValidatorBootloaders;
use Installer\Package\Package;
use Installer\Package\Packages;
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
                new Package(
                    package: Packages::SpiralValidator,
                    generators: [new SpiralValidatorBootloaders()]
                ),
            ]),
            new Option(name: 'Symfony Validator', packages: [
                new Package(
                    package: Packages::SymfonyValidator,
                    generators: [new SymfonyValidatorBootloaders()]
                ),
            ]),
            new Option(name: 'Laravel Validator', packages: [
                Packages::LaravelValidator,
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
