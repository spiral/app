<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;

class DemoTest extends TestCase
{
    public function testDemo(): void
    {
        $expected = true;
        $actual = false;

        $this->assertTrue($expected);
        $this->assertFalse($actual);
    }
}
