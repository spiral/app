<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;

use Spiral\Bootloader\I18nBootloader;
use Tests\TestCase;
use Spiral\Testing\Http\FakeHttp;

class HomeControllerTest extends TestCase
{
    private FakeHttp $http;

    public function testDefaultActionWorks(): void
    {
        $response = $this->http->get('/')->assertOk();

        $this->assertStringContainsString(
            'The PHP Framework for future Innovators',
            \strip_tags((string) $response->getOriginalResponse()->getBody()),
        );
    }

    public function testDefaultActionWithRuLocale(): void
    {
        if (!\in_array(I18nBootloader::class, $this->getRegisteredBootloaders())) {
            $this->markTestSkipped('Component `spiral/translator` is not installed.');
        }

        $response = $this->http->withHeader('accept-language', 'ru')->get('/')->assertOk();

        $this->assertStringContainsString(
            'PHP Framework для будущих инноваторов',
            \strip_tags((string) $response->getOriginalResponse()->getBody()),
        );
    }

    public function testInteractWithConsole(): void
    {
        $output = $this->runCommand('views:reset');

        $this->assertStringContainsString('cache', $output);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = $this->fakeHttp();
    }
}
