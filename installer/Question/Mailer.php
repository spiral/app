<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Component\Generator\Mailer\Bootloaders;
use Installer\Component\Generator\Mailer\Env;
use Installer\Question\Option\BooleanOption;
use Installer\Question\Option\OptionInterface;

final class Mailer extends AbstractQuestion
{
    /**
     * @param OptionInterface[] $options
     */
    public function __construct(
        string $question = 'Do you want to use Mailer Component?',
        bool $required = false,
        array $options = [
            new BooleanOption(name: 'Yes', generators: [
                new Bootloaders(),
                new Env(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
