<?php

declare(strict_types=1);

namespace Installer\Internal\Readme\Block;

use Stringable;

final class CodeBlock implements Stringable
{
    /**
     * @param non-empty-string|Stringable $content
     * @param non-empty-string|null $title
     */
    public function __construct(
        private readonly string|Stringable $content,
        private readonly string $language = 'php',
        private readonly ?string $title = null,
    ) {
    }

    public function __toString(): string
    {
        if ($this->content === '') {
            return '';
        }

        $content = '';
        if ($this->title !== null) {
            $content = \sprintf("### %s\n\n", $this->title);
        }

        $content .= \sprintf("```%s\n%s\n```", $this->language, $this->content);

        return $content;
    }
}
