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
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Set;
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
        $this->beforeStarting(static function (ConfiguratorInterface $config): void {
            if (! $config->exists('session')) {
                return;
            }

            $config->modify('session', new Set('handler', null));
        });

        parent::setUp();

        $this->views = $this->getContainer()->get(ViewsInterface::class);
        $this->getContainer()->get(TranslatorInterface::class)->setLocale('en');
    }

    protected function tearDown(): void
    {
        // Uncomment this line if you want to clean up runtime directory.
        // $this->cleanUpRuntimeDirectory();
    }

    public function rootDirectory(): string
    {
        return __DIR__.'/..';
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
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
