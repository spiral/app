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
        $this->http = $this->app->get(HTTP::class);
        $this->views = $this->app->get(ViewsInterface::class);
        $this->app->get(TranslatorInterface::class)->setLocale('en');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $fs = new Files();

        if ($fs->isDirectory(__DIR__ . '/../app/migrations')) {
            $fs->deleteDirectory(__DIR__ . '/../app/migrations');
        }

        $runtime = $this->app->get(DirectoriesInterface::class)->get('runtime');
        if ($fs->isDirectory($runtime)) {
            $fs->deleteDirectory($runtime);
        }
    }

    protected function makeApp(array $env = []): TestApp
    {
        return TestApp::init([
            'root' => __DIR__ . '/../',
            'app' => __DIR__ . '/../app',
            'runtime' => __DIR__ . '/../runtime/tests',
            'cache' => __DIR__ . '/../runtime/tests'
        ], new Environment($env), false);
    }
}
