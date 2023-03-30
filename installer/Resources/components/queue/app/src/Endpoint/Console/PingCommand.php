<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Endpoint\Job\Ping;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Attribute\Question;
use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Spiral\Queue\QueueInterface;

/**
 * Simple command to demonstrate how to push a job to a queue.
 *
 * To execute this command run:
 * php app.php ping site.com
 *
 * Run `php app.php help ping` to see all available options.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[AsCommand(name: 'ping', description: 'Simple command to demonstrate how to push a job to a queue.')]
final class PingCommand extends Command
{
    #[Argument(description: 'Site url')]
    #[Question(question: 'Provide a site url')]
    private string $site;

    public function __invoke(QueueInterface $queue): int
    {
        $id = $queue->push(Ping::class, [
            'site' => $this->site,
        ]);

        $this->info(
            \sprintf('Job [%s] has been successfully pushed to a queue.', $id),
        );

        return self::SUCCESS;
    }
}
