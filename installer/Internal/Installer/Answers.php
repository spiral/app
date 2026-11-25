<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\Question\QuestionInterface;

/**
 * Answers given in advance through the `SPIRAL_INSTALLER_ANSWERS` environment variable, so that
 * `composer create-project` can run without prompts:
 *
 *     {"application": "Web", "questions": {"EventDispatcher": 1, "Validators": 0}}
 *
 * A question key is its class name without the `Installer\Module\` or `Installer\Application\` prefix
 * and the `\Question` suffix, e.g. `CycleBridge/CollectionsQuestion`; `/` may replace `\`.
 * An answer is the option number, `0` declines. Questions without an answer are asked as usual.
 */
final class Answers
{
    public const ENV = 'SPIRAL_INSTALLER_ANSWERS';

    /**
     * @param array<non-empty-string, int> $questions Question key => option number
     */
    public function __construct(
        public readonly ?string $application = null,
        private readonly array $questions = [],
    ) {}

    /**
     * @throws \RuntimeException When the variable is not a valid answers object
     */
    public static function fromEnv(): self
    {
        $value = \getenv(self::ENV);

        return \is_string($value) && $value !== '' ? self::fromJson($value) : new self();
    }

    /**
     * @throws \RuntimeException When the JSON is not a valid answers object
     */
    public static function fromJson(string $json): self
    {
        try {
            $data = \json_decode($json, true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException(\sprintf('%s is not valid JSON: %s', self::ENV, $e->getMessage()), 0, $e);
        }

        \is_array($data) or throw new \RuntimeException(\sprintf('%s must be a JSON object.', self::ENV));

        $application = $data['application'] ?? null;
        $application === null || \is_string($application) or throw new \RuntimeException(
            \sprintf('%s: "application" must be a string.', self::ENV),
        );

        $questions = [];
        foreach ((array) ($data['questions'] ?? []) as $key => $answer) {
            $answer = match (true) {
                $answer === true => 1,
                $answer === false => 0,
                \is_int($answer) => $answer,
                default => throw new \RuntimeException(
                    \sprintf('%s: the answer to "%s" must be an option number.', self::ENV, $key),
                ),
            };
            $questions[self::normalizeKey((string) $key)] = $answer;
        }

        return new self($application, $questions);
    }

    public static function key(QuestionInterface $question): string
    {
        $key = \preg_replace('/^Installer\\\\(?:Module|Application)\\\\/', '', $question::class);

        return self::normalizeKey(\preg_replace('/\\\\Question$/', '', (string) $key));
    }

    public function isEmpty(): bool
    {
        return $this->application === null && $this->questions === [];
    }

    /**
     * @return int|null The option number, or null when the question has no answer
     */
    public function get(QuestionInterface $question): ?int
    {
        return $this->questions[self::key($question)] ?? null;
    }

    /**
     * @param iterable<QuestionInterface> $questions Questions of the chosen application
     *
     * @throws \RuntimeException When an answer names no question of the application
     */
    public function assertKnown(iterable $questions): void
    {
        $known = [];
        foreach ($questions as $question) {
            $known[self::key($question)] = true;
        }

        $unknown = \array_diff_key($this->questions, $known);
        $unknown === [] or throw new \RuntimeException(\sprintf(
            '%s: unknown questions %s; available: %s.',
            self::ENV,
            \implode(', ', \array_keys($unknown)),
            \implode(', ', \array_keys($known)),
        ));
    }

    private static function normalizeKey(string $key): string
    {
        return \strtolower(\str_replace('\\', '/', $key));
    }
}
