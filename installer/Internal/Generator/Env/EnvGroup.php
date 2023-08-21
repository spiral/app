<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Env;

use Traversable;

final class EnvGroup implements \Stringable, \IteratorAggregate
{
    /**
     * @param array<non-empty-string, mixed> $values
     * @param ?non-empty-string $comment
     */
    public function __construct(
        public array $values = [],
        public readonly ?string $comment = null,
        public readonly int $priority = 0
    ) {
    }

    public function __toString(): string
    {
        $comment = $this->comment !== null ? PHP_EOL . '# ' . $this->comment . PHP_EOL : PHP_EOL;

        $values = [];
        foreach ($this->values as $key => $value) {
            $values[] = \sprintf(
                '%s=%s',
                $key,
                match (true) {
                    \is_bool($value) => \var_export($value, true),
                    \is_array($value) => \implode(',', $value),
                    \is_null($value) => \strtolower(\var_export($value, true)),
                    $value instanceof \Stringable => (string)$value,
                    $value instanceof \JsonSerializable => \json_encode($value),
                    default => $value
                }
            );
        }

        $string = \implode(PHP_EOL, $values);

        if ($string === '') {
            return '';
        }

        return $comment . \implode(PHP_EOL, $values);
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

    public function getIterator(): Traversable
    {
        foreach ($this->values as $key => $value) {
            yield $key => $value;
        }
    }
}
