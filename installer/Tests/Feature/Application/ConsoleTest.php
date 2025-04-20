<?php

declare(strict_types=1);

namespace Feature\Application;

use Installer\Application\Cli\Application;
use Installer\Module;
use Tests\Feature\InstallerTestCase;
use Tests\Module as TestModule;

final class ConsoleTest extends InstallerTestCase
{
    public function testDefaultInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\Exception())
            ->run();

        // Store log for debugging in application directory
        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // processed generators
            ->assertGeneratorProcessed(\Installer\Application\Cli\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(\Installer\Application\Cli\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\Console\Generator\Skeleton::class)

            // not processed generators
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(\Installer\Application\GRPC\Generator\Bootloaders::class)

            // registered bootloaders
            ->assertBootloaderRegistered(\Spiral\Debug\Bootloader\DumperBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Boot\Bootloader\CoreBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\DotEnv\Bootloader\DotenvBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Monolog\Bootloader\MonologBootloader::class)
            ->assertBootloaderRegistered(\App\Application\Bootloader\LoggingBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\SnapshotsBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\CommandBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Scaffolder\Bootloader\ScaffolderBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Prototype\Bootloader\PrototypeBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\EncrypterBootloader::class)

            // not registered bootloaders
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\AppBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\RoutesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Security\FiltersBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Security\GuardBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\RouterBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\JsonPayloadsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CookiesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\SessionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CsrfBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\PaginationBootloader::class)

            ->assertCopied(
                'Application/Cli/resources/skeleton/app/src/Endpoint/Console/DoNothing.php',
                'app/src/Endpoint/Console/DoNothing.php',
            )
            ->assertCopied(
                'Module/Exception/resources/app.php',
                'app.php',
            )
            ->assertCopied(
                'Module/Exception/resources/app/src/Application/Exception/Handler.php',
                'app/src/Application/Exception/Handler.php',
            )

            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!');
    }
}
