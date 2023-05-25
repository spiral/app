<?php

declare(strict_types=1);

namespace Installer\Internal;

final class EventStorage
{
    private array $events = [];

    public function addEvent(object $event): void
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
