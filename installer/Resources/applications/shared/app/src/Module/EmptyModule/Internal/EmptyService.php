<?php

declare(strict_types=1);

namespace App\Module\EmptyModule\Internal;

/**
 * @psalm-internal App\Module\EmptyModule\Internal
 */
class EmptyService implements \App\Module\EmptyModule\Api\EmptyService
{
    public function doNothing(): void
    {
    }
}
