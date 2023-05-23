<?php

declare(strict_types=1);

namespace Installer\Internal\Generator;

final class EnvGroup
{
    /**
     * @param array<non-empty-string, mixed> $values
     * @param ?non-empty-string $comment
     */
    public function __construct(
        private array $values = [],
        private readonly ?string $comment = null,
        public readonly int $priority = 0
    ) {
    }

    /**
     * @param non-empty-string $key
     */
    public function addValue(string $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * @param non-empty-string $key
     */
    public function hasValue(string $key): bool
    {
        return isset($this->values[$key]);
    }

    public function render(): string
    {
        $comment = $this->comment !== null ? PHP_EOL . '# ' . $this->comment . PHP_EOL : PHP_EOL;

        $values = [];
        foreach ($this->values as $key => $value) {
            $values[] = \sprintf('%s=%s', $key, match (true) {
                \is_bool($value) => \var_export($value, true),
                default => $value
            });
        }

        return $comment . \implode(PHP_EOL, $values);
    }
}
