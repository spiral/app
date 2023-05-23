<?php

declare(strict_types=1);

namespace Installer\Internal\Console;

use Composer\IO\IOInterface;

final class IO
{
    public function __construct(
        private readonly IOInterface $io,
    ) {
    }

    public function isVerbose(): bool
    {
        return $this->io->isVerbose();
    }

    public function ask(string $question, string|int|null $default = null): string|int
    {
        return $this->io->ask($question, $default);
    }

    public function title(string $title): void
    {
        $this->io->write(\sprintf('  <question>%s</question>', $title));
    }

    public function write(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        $this->io->write($message, $newline, $verbosity);
    }

    public function error(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        $this->io->write(\sprintf('  <error>%s</error>', $message), $newline, $verbosity);
    }

    public function comment(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        $this->io->write(\sprintf('  <comment>%s</comment>', $message), $newline, $verbosity);
    }

    public function info(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        $this->io->write(\sprintf('  <info>%s</info>', $message), $newline, $verbosity);
    }

    public function success(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        if (!$this->isVerbose()) {
            return;
        }

        $this->io->write(\sprintf('  <info>%s</info>', $message), $newline, $verbosity);
    }

    public function debug(string $message, bool $newline = true, int $verbosity = IOInterface::NORMAL): void
    {
        if (!$this->isVerbose()) {
            return;
        }

        $this->io->write(\sprintf('  %s', $message), $newline, $verbosity);
    }
}
