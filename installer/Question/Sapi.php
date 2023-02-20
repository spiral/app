<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\SapiBridge;
use Installer\Question\Option\Option;

final class Sapi extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Would you like to use SAPI?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new SapiBridge(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }

    public function getHelp(): ?string
    {
        return <<<'HELP'
If you're using servers like Nginx, Apache, or IIS, choose this option. Your app will shut down after it finishes 
processing requests. If you want to learn about Long Running, check out the documentation at
https://spiral.dev/docs/start-server
HELP;
    }
}
