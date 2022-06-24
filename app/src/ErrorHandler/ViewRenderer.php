<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Http\ErrorHandler\RendererInterface;
use Spiral\Http\Header\AcceptHeader;
use Spiral\Views\Exception\ViewException;
use Spiral\Views\ViewsInterface;

class ViewRenderer implements RendererInterface
{
    private const GENERAL_VIEW = 'exception/error';
    private const VIEW = 'exception/%s';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function renderException(Request $request, int $code, \Throwable $exception): ResponseInterface
    {
        $acceptItems = AcceptHeader::fromString($request->getHeaderLine('Accept'))->getAll();
        if ($acceptItems && $acceptItems[0]->getValue() === 'application/json') {
            return $this->renderJson($code, $exception);
        }

        return $this->renderView($code, $exception);
    }

    private function renderJson(int $code, \Throwable $exception): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);

        $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
        $response->getBody()->write(\json_encode(['status' => $code, 'error' => $exception->getMessage()]));

        return $response;
    }

    private function renderView(int $code, \Throwable $exception): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code);

        try {
            $view = $this->views->get(\sprintf(self::VIEW, $code));
        } catch (ViewException) {
            $view = $this->views->get(self::GENERAL_VIEW);
        }

        $content = $view->render(['code' => $code, 'exception' => $exception]);
        $response->getBody()->write($content);

        return $response;
    }
}
