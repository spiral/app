<?php

declare(strict_types=1);

namespace Installer\Internal\Readme\Block;

final class LinkString implements \Stringable
{
    public function __construct(
        private readonly string $content,
        private readonly string $link,
    ) {}

    public function __toString(): string
    {
        return \sprintf('[%s](%s)', $this->content, $this->link);
    }
}
