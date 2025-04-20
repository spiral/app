<?php

declare(strict_types=1);

namespace Tests\Fixtures;

final class ExceptionHandlerBootloaderWithBindings
{
    protected const BINDINGS = [
        'Foo' => 'Bar',
    ];
}
