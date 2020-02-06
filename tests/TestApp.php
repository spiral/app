<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests;

use App\App;

class TestApp extends App
{
    public function get($alias, string $context = null)
    {
        return $this->container->get($alias, $context);
    }
}
