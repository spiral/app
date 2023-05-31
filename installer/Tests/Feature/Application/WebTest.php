<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Application\Web\Application;
use Installer\Module;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Sapi\Bootloader\SapiBootloader;
use Spiral\SendIt\Bootloader\MailerBootloader;
use Spiral\Session\Middleware\SessionMiddleware;
use Tests\Feature\InstallerTestCase;

final class WebTest extends InstallerTestCase
{
    public function testInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->addAnswer(Module\SapiBridge\Question::class, true)
            ->addAnswer(Module\Cache\Question::class, true)
            ->addAnswer(Module\Queue\Question::class, true)
            ->addAnswer(Module\Translator\Question::class, true)
            ->run();

        $result
            ->assertDeleted('LICENSE')
            ->assertGeneratorProcessed(\Installer\Module\SapiBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\Mailer\Generator\Bootloaders::class)
            ->assertPackageInstalled('ext-sockets')
            ->assertPackageInstalled('spiral/dumper')
            ->assertEnvDefined('QUEUE_CONNECTION', 'in-memory')
            ->assertEnvNotDefined('MAILER_FROM')
            ->assertFileExists('app/config/queue.php')
            ->assertCopied(
                'Module/Translator/resources/locale/ru/messages.en.php',
                'app/locale/ru/messages.en.php'
            )
            ->assertBootloaderRegistered(SapiBootloader::class)
            ->assertBootloaderNotRegistered(MailerBootloader::class)
            ->assertMiddlewareRegistered(ErrorHandlerMiddleware::class)
            ->assertMiddlewareNotRegistered(HttpCollector::class, 'web')
            ->assertMiddlewareRegistered(SessionMiddleware::class, 'web')
            ->assertMiddlewareRegistered(LocaleSelector::class)
            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!')
            ->assertCommandExecuted('rr make-config -p http -p jobs -p kv')
            ->assertReadmeContains('The settings for RoadRunner are in a file named .rr.yaml at the main folder of the app.');

        // Store log for debugging in application directory
        $result->storeLog();
    }
}
