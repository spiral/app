<?php

declare(strict_types=1);

namespace Installer\Module\Psr7Implementation;

use Installer\Internal\Question\AbstractQuestion;
use Installer\Internal\Question\Option\Option;
use Installer\Module\Psr7Implementation\Nyholm\Package as NyholmPackage;

final class Question extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you need HTTP PSR-7 implementation?',
        bool $required = false,
        array $options = [
            new Option(name: 'nyholm/psr7', packages: [
                new NyholmPackage(),
            ]),
        ],
    ) {
        parent::__construct($question, $required, $options);
    }

    public function getHelp(): ?string
    {
        return <<<'HELP'
        If you need to use HTTP, you can choose a PSR-7 implementation. We provide a bridge for nyholm/psr7 package.
        HELP;
    }
}
