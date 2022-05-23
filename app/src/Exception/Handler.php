<?php

declare(strict_types=1);

namespace App\Exception;

use Spiral\Exceptions\ExceptionHandler;

final class Handler extends ExceptionHandler
{
    public function __construct()
    {
        parent::__construct();

        $this->addRenderer(new CollisionRenderer());
    }
}
