<?php

declare(strict_types=1);

namespace Installer\Application\Web\Generator;

use App\Application\Exception\Renderer\ViewRenderer as Renderer;
use Installer\Internal\Application\ApplicationInterface;
use Installer\Internal\Generator\Context;
use Installer\Internal\Generator\GeneratorInterface;
use Installer\Module\TemplateEngines\Stempler\Package as StemplerPackage;
use Installer\Module\TemplateEngines\Twig\Package as TwigPackage;
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
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, Renderer::class);
        } else {
            $context->exceptionHandlerBootloader->addBinding(RendererInterface::class, PlainRenderer::class);
        }
    }

    private function isTemplateEngineInstalled(ApplicationInterface $application): bool
    {
        return $application->isPackageInstalled(new StemplerPackage())
            || $application->isPackageInstalled(new TwigPackage());
    }
}
