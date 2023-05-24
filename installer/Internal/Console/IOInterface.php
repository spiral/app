<?php

declare(strict_types=1);

namespace Installer\Internal\Console;

interface IOInterface
{
    public function isVerbose(): bool;

    public function ask(string $question, string|int|null $default = null): string|int;

    public function title(string $title): void;

    public function write(string $message, bool $newline = true): void;

    public function error(string $message, bool $newline = true): void;

    public function comment(string $message, bool $newline = true): void;

    public function info(string $message, bool $newline = true): void;

    public function success(string $message, bool $newline = true): void;

    public function debug(string $message, bool $newline = true): void;
}
