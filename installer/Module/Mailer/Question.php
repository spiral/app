<?php

declare(strict_types=1);

namespace Installer\Module\Mailer;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Internal\Question\Option\OptionInterface;
use Installer\Module\Mailer\Generator\Bootloaders;
use Installer\Module\Mailer\Generator\Config;

final class Question extends AbstractQuestion
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
                new Config(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }
}
