<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    public function testDefaultActionWorks(): void
    {
        $want = 'Welcome to Spiral Framework<';
        $got = (string)$this->get('/')->getBody();

        $this->assertStringContainsString($want, $got);
    }

    public function testDefaultActionWithRuLocale(): void
    {
        $want = 'Вас приветствует Spiral Framework';
        $got = (string)$this->get('/', [], [ 'accept-language' => 'ru'])->getBody();

        $this->assertStringContainsString($want, $got);
    }

    public function testInteractWithConsole(): void
    {
        $output = $this->runCommand('views:reset');

        $this->assertStringContainsString('cache', $output);
    }
}
