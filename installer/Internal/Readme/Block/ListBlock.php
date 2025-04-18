<?php

declare(strict_types=1);

namespace Installer\Internal\Readme\Block;

final class ListBlock implements \Stringable
{
    /**
     * @param non-empty-string[]|\Stringable[] $items
     * @param non-empty-string|null $title
     */
    public function __construct(
        private readonly array $items,
        private readonly ?string $title = null,
    ) {}

    public function __toString(): string
    {
        if ($this->items === []) {
            return '';
        }

        $list = '';
        if ($this->title !== null) {
            $list = \sprintf("### %s\n\n", $this->title);
        }

        foreach ($this->items as $item) {
            $list .= \sprintf("- %s\n", $item);
        }

        return $list . "\n\n";
    }
}
