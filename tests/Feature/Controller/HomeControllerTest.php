<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function testDefaultActionWorks(): void
    {
        $this
            ->fakeHttp()
            ->get('/')
            ->assertOk()
            ->assertBodyContains('The PHP Framework for future Innovators');
    }
}
