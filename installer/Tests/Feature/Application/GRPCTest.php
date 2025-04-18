<?php

declare(strict_types=1);

namespace Feature\Application;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Application\GRPC\Application;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Session\Middleware\SessionMiddleware;
use Tests\Feature\InstallerTestCase;
use Tests\Module as TestModule;

final class GRPCTest extends InstallerTestCase
{
    public function testDefaultInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\ExtSockets())
            ->addModule(new TestModule\ExtGRPC())
            ->addModule(new TestModule\RoadRunnerBridge())
            ->addModule(new TestModule\RoadRunnerGRPC())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\Exception())
            ->run();

        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // processed generators
            ->assertGeneratorProcessed(\Installer\Application\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(ExceptionSkeleton::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)

            // not processed generators
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Skeleton::class)

            // registered bootloaders
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
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\FiltersBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\GuardBootloader::class)

            // not registered bootloaders
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Views\TranslatedCacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\I18nBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\PaginationBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CsrfBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\SessionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CookiesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\JsonPayloadsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\RouterBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\RoutesBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\AppBootloader::class)


            ->assertCopied(
                'Application/Common/resources/app/config/scaffolder.php',
                'app/config/scaffolder.php',
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Bootloader/LoggingBootloader.php',
                'app/src/Application/Bootloader/LoggingBootloader.php',
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Bootloader/ExceptionHandlerBootloader.php',
                'app/src/Application/Bootloader/ExceptionHandlerBootloader.php',
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Kernel.php',
                'app/src/Application/Kernel.php',
            )
            ->assertNotCopied(
                'Application/Web/Generator/resources/ViewRenderer.php',
                'app/src/Application/Exception/Renderer/ViewRenderer.php',
            )

            ->assertMiddlewareNotRegistered(ErrorHandlerMiddleware::class)
            ->assertMiddlewareNotRegistered(HttpCollector::class, 'web')
            ->assertMiddlewareNotRegistered(SessionMiddleware::class, 'web')
            ->assertMiddlewareNotRegistered(LocaleSelector::class)
            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!')
            ->assertCommandExecuted('rr download-protoc-binary')
            ->assertReadmeContains('### GRPC')
            ->assertReadmeContains('- Configuration file: `app/config/grpc.php`')
            ->assertReadmeContains('Download or update protoc-gen GRPC plugin')
            ->assertReadmeContains('Generate gRPC proto files');
    }
}
