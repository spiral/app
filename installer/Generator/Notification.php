<?php

declare(strict_types=1);

namespace Installer\Generator;

use Composer\IO\IOInterface;

final class Notification
{
    public function __construct(
        private readonly IOInterface $io
    ) {
    }

    /**
     * @var Message[]
     */
    private array $messages = [];

    /**
     * @param non-empty-string $title
     * @param non-empty-string $message
     */
    public function addMessage(string $title, string $message, int $priority = 0): void
    {
        $this->messages[] = new Message($title, $message, $priority);
    }

    public function __destruct()
    {
        \uasort($this->messages, static fn (Message $a, Message $b) => $a->priority <=> $b->priority);

        $this->io->write(PHP_EOL);

        foreach ($this->messages as $message) {
            $this->io->write(\sprintf("  <comment>%s</comment>", $message->title));
            $this->io->write($message->message);
        }

        $this->io->write(PHP_EOL);
    }
}
