<?php

declare(strict_types=1);

namespace Installer\Question;

use Installer\Package\LeagueEvent;
use Installer\Question\Option\Option;

final class EventDispatcher extends AbstractQuestion
{
    /**
     * @param Option[] $options
     */
    public function __construct(
        string $question = 'Do you want to use the Event Dispatcher?',
        bool $required = false,
        array $options = [
            new Option(name: 'Yes', packages: [
                new LeagueEvent(),
            ]),
        ]
    ) {
        parent::__construct($question, $required, $options);
    }

    public function getHelp(): ?string
    {
        return <<<'HELP'
The Event Dispatcher is a simple PHP event system that allows you to listen for and trigger events in your application.
  We use the League\Event - https://event.thephpleague.com/3.0/ package for this purpose.
  Documentation: https://spiral.dev/docs/advanced-events
HELP;
    }
}
