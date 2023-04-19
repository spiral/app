<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Spiral\Boot\Environment\AppEnvironment;
use Spiral\Prototype\Traits\PrototypeTrait;

final class PageNotFoundController
{
    use PrototypeTrait;

    public function __invoke(string $path, AppEnvironment $env): string
    {
        return $this->views->render('exception/404', [
            'path' => $path,
            'debug' => !$env->isProduction()
        ]);
    }
}
