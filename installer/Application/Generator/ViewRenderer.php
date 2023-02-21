<?php

declare(strict_types=1);

namespace Installer\Application\Generator;

use Installer\Application\ApplicationInterface;
use Installer\Generator\Context;
use Installer\Generator\GeneratorInterface;
use Installer\Package\StemplerBridge;
use Installer\Package\TwigBridge;
use App\Application\Exception\Renderer\ViewRenderer as Renderer;
use Spiral\Http\ErrorHandler\PlainRenderer;
use Spiral\Http\ErrorHandler\RendererInterface;

final class ViewRenderer implements GeneratorInterface
{
    private const RENDERER_PATH = 'applications/shared/app/src/Application/Exception/Renderer/ViewRenderer.php';
    private const TARGET_PATH = 'app/src/Application/Exception/Renderer/ViewRenderer.php';

    public function process(Context $context): void
    {
        $context->exceptionHandlerBootloader->addUse(RendererInterface::class);

        if ($this->isTemplateEngineInstalled($context->application)) {
            $context->resource->copy(self::RENDERER_PATH, self::TARGET_PATH);
            $context->exceptionHandlerBootloader->addUse(Renderer::class);
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, Renderer::class);
        } else {
            $context->exceptionHandlerBootloader->addUse(PlainRenderer::class);
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, PlainRenderer::class);
        }
    }

    private function isTemplateEngineInstalled(ApplicationInterface $application): bool
    {
        return $application->isPackageInstalled(new StemplerBridge())
            || $application->isPackageInstalled(new TwigBridge());
    }
}
