<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Endpoint\Job\PingHandler;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Question;
use Spiral\Console\Command;
use Spiral\Queue\QueueInterface;

#[AsCommand(name: 'ping', description: 'Ping some url')]
final class PingCommand extends Command
{
    #[Argument(description: 'Url to ping')]
    #[Question(question: 'Specify url to ping')]
    private string $url;

    public function __invoke(QueueInterface $queue): int
    {
        // Put your command logic here
        $this->info(\sprintf('Pinging %s...', $this->url));

        /** @psalm-suppress InvalidArgument */
        $queue->push(PingHandler::class, new Site(url: $this->url));

        return self::SUCCESS;
    }
}
