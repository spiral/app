<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\Generator\Mailer\Bootloaders;
use Installer\Package\Generator\Mailer\Env;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\Option;

final class Mailer extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Mailer Component?',
        bool $required = false,
        array $options = [
            new BooleanOption(
                name: 'Yes',
                generators: [
                    new Bootloaders(),
                    new Env(),
                ]
            ),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }
}
