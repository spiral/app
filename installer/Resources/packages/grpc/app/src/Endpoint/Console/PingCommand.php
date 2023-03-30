<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use GRPC\Ping\PingRequest;
use GRPC\Ping\PingServiceInterface;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Question;
use Spiral\Console\Command;
use Spiral\RoadRunner\GRPC\Context;

/**
 * To execute this command run:
 * php app.php ping site.com
 *
 * Run `php app.php help ping` to see all available options.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[AsCommand(name: 'ping', description: 'Push ping job to a queue.')]
final class PingCommand extends Command
{
    #[Argument(description: 'Site url')]
    #[Question(question: 'Provide a site url')]
    private string $site;

    public function __invoke(PingServiceInterface $service): int
    {
        $result = $service->PingUrl(
            new Context([]),
            new PingRequest(['url' => $this->site]),
        );

        $this->info(
            \sprintf(
                'Site [%s] has been successfully pinged. Status code: %d',
                $this->site,
                $result->getStatus(),
            ),
        );

        return self::SUCCESS;
    }
}
