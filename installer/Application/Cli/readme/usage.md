To create your first command effortlessly, use the scaffolding command:

```bash
php app.php create:command FirstCommand
```

After executing this command, a new command class will be created in the `src/Endpoint/Console/Command` directory. The
class will look like this:

```php
namespace App\Endpoint\Console;

use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Attribute\Question;
use Spiral\Console\Command;

#[AsCommand(name: 'first')]
final class FirstCommand extends Command
{
    public function __invoke(): int
    {
        // Put your command logic here
        $this->info('Command logic is not implemented yet');

        return self::SUCCESS;
    }
}
```

To invoke your command, run the following command in the console:

```bash
php app.php first
```

Read more about commands in the [Spiral documentation](https://spiral.dev/docs/console-commands).
