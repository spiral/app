<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use App\Application\Service\ErrorHandler\ViewRenderer as Renderer;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Package\StemplerBridge;
use Installer\Package\TwigBridge;
use Spiral\Http\ErrorHandler\PlainRenderer;
use Spiral\Http\ErrorHandler\RendererInterface;

final class ViewRenderer implements GeneratorInterface
{
    private const RENDERER_PATH = 'applications/shared/Application/Service/ErrorHandler/ViewRenderer.php';
    private const TARGET_PATH = 'app/src/Application/Service/ErrorHandler/ViewRenderer.php';

    public function process(Context $context): void
    {
        $context->exceptionHandlerBootloader->addUse(RendererInterface::class);

        if ($this->isTemplateEngineInstalled($context->composerDefinition['extra']['spiral']['packages'] ?? [])) {
            $context->resource->copy(self::RENDERER_PATH, self::TARGET_PATH);
            $context->exceptionHandlerBootloader->addUse(Renderer::class);
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, Renderer::class);
        } else {
            $context->exceptionHandlerBootloader->addUse(PlainRenderer::class);
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, PlainRenderer::class);
        }
    }

    private function isTemplateEngineInstalled(array $packages): bool
    {
        return \in_array((new StemplerBridge())->getName(), $packages, true)
            || \in_array((new TwigBridge())->getName(), $packages, true);
    }
}
