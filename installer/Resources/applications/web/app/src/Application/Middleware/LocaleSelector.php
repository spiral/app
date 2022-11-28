<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Translator\Translator;

class LocaleSelector implements MiddlewareInterface
{
    /** @var string[] */
    private array $availableLocales;

    public function __construct(
        private readonly Translator $translator
    ) {
        $this->availableLocales = $this->translator->getCatalogueManager()->getLocales();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $defaultLocale = $this->translator->getLocale();

        try {
            foreach ($this->fetchLocales($request) as $locale) {
                if ($locale !== '' && \in_array($locale, $this->availableLocales, true)) {
                    $this->translator->setLocale($locale);
                    break;
                }
            }

            return $handler->handle($request);
        } finally {
            // restore
            $this->translator->setLocale($defaultLocale);
        }
    }

    public function fetchLocales(ServerRequestInterface $request): \Generator
    {
        $header = $request->getHeaderLine('accept-language');
        foreach (\explode(',', $header) as $value) {
            $pos = \strpos($value, ';');
            if ($pos!== false) {
                yield \substr($value, 0, $pos);
            }

            yield $value;
        }
    }
}
