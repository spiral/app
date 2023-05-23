<?php

declare(strict_types=1);

namespace Installer\Internal\Console;

final class Output implements \Stringable
{
    public function __construct(
        private readonly string $message,
        private readonly string $type = 'write',
    ) {
    }

    public function __toString(): string
    {
        return \sprintf('[%s] %s', $this->type, $this->message);
    }

    public function send(IO $output): void
    {
        $output->{$this->type}($this->message);
    }

    public static function write(string $message): self
    {
        return new self($message, 'write');
    }

    public static function error(string $message): self
    {
        return new self($message, 'error');
    }

    public static function success(string $message): self
    {
        return new self($message, 'success');
    }

    public static function comment(string $message): self
    {
        return new self($message, 'comment');
    }
}
