<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment;
use Spiral\Files\Files;
use Spiral\Http\Http;
use Spiral\Translator\TranslatorInterface;
use Spiral\Views\ViewsInterface;
use Tests\Traits\InteractsWithConsole;
use Tests\Traits\InteractsWithHttp;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithConsole;
    use InteractsWithHttp;

    /** @var \Spiral\Boot\AbstractKernel */
    protected $app;

    /** @var \Spiral\Http\Http */
    protected $http;

    /** @var \Spiral\Views\ViewsInterface */
    protected $views;

    protected function setUp(): void
    {
        $this->app = $this->makeApp();
        $this->http = $this->app->get(Http::class);
        $this->views = $this->app->get(ViewsInterface::class);
        $this->app->get(TranslatorInterface::class)->setLocale('en');
    }

    protected function tearDown(): void
    {
        $fs = new Files();

        $runtime = $this->app->get(DirectoriesInterface::class)->get('runtime');
        if ($fs->isDirectory($runtime)) {
            $fs->deleteDirectory($runtime);
        }
    }

    protected function makeApp(array $env = []): TestApp
    {
        $root = dirname(__DIR__);

        return TestApp::init([
            'root' => $root,
            'app' => $root . '/app',
            'runtime' => $root . '/runtime/tests',
            'cache' => $root . '/runtime/tests/cache',
        ], new Environment($env), false);
    }
}
