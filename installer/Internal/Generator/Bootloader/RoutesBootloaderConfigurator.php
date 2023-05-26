<?php

declare(strict_types=1);

namespace Installer\Internal\Generator\Bootloader;

use App\Application\Bootloader\RoutesBootloader;
use Installer\Internal\ClassMetadataInterface;
use Installer\Internal\Events\MiddlewareInjected;
use Installer\Internal\EventStorage;
use Installer\Internal\Generator\Kernel\ClassListGroup;
use Installer\Internal\ReflectionClassMetadata;
use Nette\PhpGenerator\Literal;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Csrf\Middleware\CsrfMiddleware;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Reactor\Exception\ReactorException;
use Spiral\Reactor\Writer;
use Spiral\Session\Middleware\SessionMiddleware;

final class RoutesBootloaderConfigurator extends BootloaderConfigurator
{
    private ClassListGroup $globalMiddleware;

    /** @var array<non-empty-string, ClassListGroup> */
    private array $middlewareGroups = [];

    public function __construct(
        Writer $writer,
        ClassMetadataInterface $class = new ReflectionClassMetadata(RoutesBootloader::class),
        ?EventStorage $eventStorage = null,
    ) {
        parent::__construct($class, $writer, $eventStorage);

        $this->globalMiddleware = new ClassListGroup([
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ]);

        $this->addMiddlewareGroup('web', [
            CookiesMiddleware::class,
            SessionMiddleware::class,
            CsrfMiddleware::class,
        ]);
    }

    public function __destruct()
    {
        $this->inject();
        $this->write();
    }

    /**
     * @param class-string[] $middleware
     */
    public function addGlobalMiddleware(array $middleware, ?string $afterMiddleware = null): void
    {
        foreach ($middleware as $class) {
            $this->globalMiddleware->append($class, $afterMiddleware);
            $afterMiddleware = $class;
        }
    }

    /**
     * @param non-empty-string $name
     * @param class-string[] $middleware
     */
    public function addMiddlewareGroup(string $name, array $middleware, ?string $afterMiddleware = null): void
    {
        if (!isset($this->middlewareGroups[$name])) {
            $this->middlewareGroups[$name] = new ClassListGroup();
        }

        foreach ($middleware as $class) {
            $this->middlewareGroups[$name]->append($class, $afterMiddleware);
            $afterMiddleware = $class;
        }
    }

    private function inject(): void
    {
        $this->injectGlobalMiddleware();
        $this->injectGroupMiddleware();

        $this->write();
    }

    private function injectGlobalMiddleware(): void
    {
        $class = $this->declaration->getClass($this->class->getName());

        foreach ($this->globalMiddleware as $middleware) {
            $this->namespace->addUse($middleware);
        }

        try {
            $method = $class->getMethod('globalMiddleware');
        } catch (ReactorException) {
            $method = $class->addMethod('globalMiddleware');
        }

        $method->setReturnType('array');

        $string = \implode(
            PHP_EOL,
            \array_map(
                static fn (string $line) => '    ' . $line,
                \explode(PHP_EOL, \trim($this->globalMiddleware->render($this->namespace)))
            )
        );

        $string = new Literal($string);

        $method->setBody(
            <<<PHP
            return [
            $string,
            ];
            PHP
        );

        $this->eventStorage?->addEvent(new MiddlewareInjected(
            $this->class->getName(),
            'global',
            $this->globalMiddleware
        ));
    }

    private function injectGroupMiddleware(): void
    {
        $class = $this->declaration->getClass($this->class->getName());

        try {
            $method = $class->getMethod('middlewareGroups');
        } catch (ReactorException) {
            $method = $class->addMethod('middlewareGroups');
        }

        $method->setReturnType('array');

        $string = '';

        foreach ($this->middlewareGroups as $name => $group) {
            $string .= \sprintf("'%s' => [", $name) . PHP_EOL;

            foreach ($group as $middleware) {
                $this->namespace->addUse($middleware);
            }

            $string .= \implode(
                PHP_EOL,
                \array_map(
                    static fn (string $line) => '    ' . $line,
                    \explode(PHP_EOL, \trim($group->render($this->namespace)))
                )
            );

            $string .= PHP_EOL . '],' . PHP_EOL;

            $this->eventStorage?->addEvent(new MiddlewareInjected(
                $this->class->getName(),
                $name,
                $group
            ));
        }

        $string = \implode(
            PHP_EOL,
            \array_map(
                static fn (string $line) => '    ' . $line,
                \explode(PHP_EOL, $string)
            )
        );

        $string = new Literal(\rtrim($string));

        $method->setBody(
            <<<PHP
            return [
            $string
            ];
            PHP
        );
    }
}
