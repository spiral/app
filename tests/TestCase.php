<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Kairee Wu (krwu)
 */

declare(strict_types=1);

namespace Tests;

use Spiral\Boot\AbstractKernel;
use Spiral\Testing\TestableKernelInterface;
use Spiral\Testing\TestCase as BaseTestCase;
use Spiral\Translator\TranslatorInterface;
use Spiral\Views\ViewsInterface;
use Tests\App\TestApp;

class TestCase extends BaseTestCase
{
    protected ViewsInterface $views;

    protected function setUp(): void
    {
        parent::setUp();

        $this->views = $this->getContainer()->get(ViewsInterface::class);
        $this->getContainer()->get(TranslatorInterface::class)->setLocale('en');
    }

    protected function tearDown(): void
    {
        $this->cleanUpRuntimeDirectory();
    }

    public function rootDirectory(): string
    {
        return \dirname(__DIR__);
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
            'app' => $root.'/App',
            'runtime' => $root.'/runtime',
            'cache' => $root.'/runtime/cache',
        ];
    }

    /**
     * @return TestableKernelInterface|AbstractKernel
     */
    public function createAppInstance(): TestableKernelInterface
    {
        return TestApp::create(
            $this->defineDirectories($this->rootDirectory()),
            false
        );
    }
}
