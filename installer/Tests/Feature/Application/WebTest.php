<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Application\Web\Application;
use Installer\Module;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Session\Middleware\SessionMiddleware;
use Tests\Feature\InstallerTestCase;
use Tests\Module as TestModule;

final class WebTest extends InstallerTestCase
{
    public function testDefaultInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->addAnswer(Module\CycleBridge\Question::class, true)
            ->addAnswer(Module\CycleBridge\CollectionsQuestion::class, 1)
            ->addAnswer(Module\Validators\Question::class, 1)
            ->addAnswer(Module\TemplateEngines\Question::class, 1)
            ->addAnswer(Module\Translator\Question::class, 1)
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\RoadRunnerBridge())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\CycleBridge())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\ExtSockets())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\YiiErrorHandler())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\Exception())
            ->addModule(new TestModule\TemplateEngines\Stempler())
            ->addModule(new TestModule\Translator())
            ->addModule(new TestModule\Validators\Spiral())
            ->run();

        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // processed generators
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorProcessed(ExceptionSkeleton::class)

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
            ->assertBootloaderRegistered(\App\Application\Bootloader\AppBootloader::class)
            ->assertBootloaderRegistered(\App\Application\Bootloader\RoutesBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\EncrypterBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\FiltersBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\GuardBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\RouterBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\JsonPayloadsBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\CookiesBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\SessionBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\CsrfBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Http\PaginationBootloader::class)

            ->assertCopied(
                'Application/Web/Generator/resources/ViewRenderer.php',
                'app/src/Application/Exception/Renderer/ViewRenderer.php'
            )
            ->assertCopied(
                'Module/Translator/resources/locale/ru/messages.en.php',
                'app/locale/ru/messages.en.php'
            )

            ->assertMiddlewareRegistered(ErrorHandlerMiddleware::class)
            ->assertMiddlewareNotRegistered(HttpCollector::class, 'web')
            ->assertMiddlewareRegistered(SessionMiddleware::class, 'web')
            ->assertMiddlewareRegistered(LocaleSelector::class)
            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!')
            ->assertCommandExecuted('rr make-config -p http')
            ->assertReadmeContains('RoadRunnerBridge')
            ->assertReadmeContains('The settings for RoadRunner are in a file `.rr.yaml` at the main folder of the app.')
            ->assertReadmeContains('RoadRunner HTTP server')
            ->assertReadmeContains('Download or update RoadRunner')
            ->assertReadmeNotContains('Generate gRPC proto files')
            ->assertReadmeNotContains('RoadRunner Queue server');
    }

    public function testStemplerTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 1)
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\RoadRunnerBridge())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\YiiErrorHandler())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\ExtSockets())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\Exception())
            ->addModule(new TestModule\TemplateEngines\Stempler())
            ->run();

        $result->storeLog();
    }

    public function testTwigTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 2)
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\RoadRunnerBridge())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\YiiErrorHandler())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\ExtSockets())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\Exception())
            ->addModule(new TestModule\TemplateEngines\Twig())
            ->run();

        $result->storeLog();
    }

    public function testPlainPHPTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 3)
            ->addModule(new TestModule\Console())
            ->addModule(new TestModule\RoadRunnerBridge())
            ->addModule(new TestModule\RoadRunnerCli())
            ->addModule(new TestModule\YiiErrorHandler())
            ->addModule(new TestModule\NyholmPsr7())
            ->addModule(new TestModule\Dumper())
            ->addModule(new TestModule\ExtSockets())
            ->addModule(new TestModule\ExtMbString())
            ->addModule(new TestModule\Exception())
            ->addModule(new TestModule\TemplateEngines\PlainPHP())
            ->run();

        $result->storeLog();
    }
}
