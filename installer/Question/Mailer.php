<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Question\Option\Option;
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
            new Option(name: 'Yes', packages: [
                new \Installer\Package\Mailer(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
