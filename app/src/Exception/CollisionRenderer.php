<?php

declare(strict_types=1);

namespace App\Exception;

use NunoMaduro\Collision\Adapters\Laravel\Inspector;
use NunoMaduro\Collision\Contracts\Handler as HandlerInterface;
use NunoMaduro\Collision\Handler;
use NunoMaduro\Collision\Provider;
use NunoMaduro\Collision\SolutionsRepositories\NullSolutionsRepository;
use NunoMaduro\Collision\Writer;
use Spiral\Exceptions\ExceptionRendererInterface;
use Spiral\Exceptions\Verbosity;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CollisionRenderer implements ExceptionRendererInterface
{
    private HandlerInterface $handler;

    public function __construct(
        private readonly int $verbosity = OutputInterface::VERBOSITY_NORMAL
    ) {
        $solutionsRepository = new NullSolutionsRepository();
        $handler = new Handler(
            new Writer($solutionsRepository)
        );

        $provider = new Provider(null, $handler);
        $this->handler = $provider->register()->getHandler();
    }

    public function render(
        \Throwable $exception,
        ?Verbosity $verbosity = Verbosity::BASIC,
        string $format = null,
    ): string {
        $this->handler->setOutput($output = new BufferedOutput());
        $output->setVerbosity($this->verbosity);

        $this->handler->setInspector((new Inspector($exception)));
        $this->handler->handle();

        return $output->fetch();
    }

    public function canRender(string $format): bool
    {
        return $format === 'cli';
    }
}
