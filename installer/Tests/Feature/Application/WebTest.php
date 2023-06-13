<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Application\Web\Application;
use Installer\Module;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Session\Middleware\SessionMiddleware;
use Tests\Feature\InstallerTestCase;

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
            ->run();

        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // installed packages
            ->assertPackageInstalled('ext-sockets')
            ->assertPackageInstalled('ext-mbstring')
            ->assertPackageInstalled('spiral/dumper')
            ->assertPackageInstalled('spiral/nyholm-bridge')
            ->assertPackageInstalled('spiral/roadrunner-bridge')
            ->assertPackageInstalled('spiral/roadrunner-cli')
            ->assertPackageInstalled('spiral-packages/yii-error-handler-bridge')
            ->assertPackageInstalled('spiral/cycle-bridge')
            ->assertPackageInstalled('doctrine/collections')
            ->assertPackageInstalled('spiral/validator')
            ->assertPackageInstalled('spiral/stempler-bridge')
            ->assertPackageInstalled('spiral/translator')

            // not installed packages
            ->assertPackageNotInstalled('ext-grpc')
            ->assertPackageNotInstalled('grpc/grpc')
            ->assertPackageNotInstalled('spiral/sapi-bridge')
            ->assertPackageNotInstalled('loophp/collection')
            ->assertPackageNotInstalled('illuminate/collections')
            ->assertPackageNotInstalled('spiral/roadrunner-grpc')
            ->assertPackageNotInstalled('spiral-packages/symfony-validator')
            ->assertPackageNotInstalled('spiral-packages/laravel-validator')
            ->assertPackageNotInstalled('spiral-packages/scheduler')
            ->assertPackageNotInstalled('spiral/temporal-bridge')
            ->assertPackageNotInstalled('spiral/sentry-bridge')
            ->assertPackageNotInstalled('spiral/twig-bridge')
            ->assertPackageNotInstalled('spiral-packages/league-event')
            ->assertPackageNotInstalled('spiral-packages/symfony-serializer')
            ->assertPackageNotInstalled('spiral-packages/serializable-closure')
            ->assertPackageNotInstalled('spiral/data-grid-bridge')

            // processed generators
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorProcessed(ConsoleSkeleton::class)
            ->assertGeneratorProcessed(ExceptionSkeleton::class)
            ->assertGeneratorProcessed(Module\CycleBridge\Generator\Bootloaders::class)
            //->assertGeneratorProcessed(Module\CycleBridge\Generator\Collections::class)
            ->assertGeneratorProcessed(Module\CycleBridge\Generator\Config::class)
            ->assertGeneratorProcessed(Module\CycleBridge\Generator\Env::class)
            ->assertGeneratorProcessed(Module\CycleBridge\Generator\Interceptors::class)
            ->assertGeneratorProcessed(Module\CycleBridge\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\Dumper\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\ErrorHandler\Yii\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Psr7Implementation\Nyholm\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\CacheConfig::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\Env::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\QueueConfig::class)
            ->assertGeneratorProcessed(Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Translator\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Translator\Generator\Env::class)
            ->assertGeneratorProcessed(Module\Translator\Generator\Middlewares::class)
            ->assertGeneratorProcessed(Module\Validators\Spiral\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Validators\Spiral\Generator\Middlewares::class)

            // not processed generators
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(\Installer\Application\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Cache\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(Module\EventDispatcher\League\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\GRPC\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\GRPC\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\Metrics\Generator\MetricsBootloader::class)
            ->assertGeneratorNotProcessed(Module\SapiBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Scheduler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\SentryBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\SentryBridge\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Serializers\LaravelSerializableClosure\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Serializers\LaravelSerializableClosure\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Serializers\SymfonySerializer\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Serializers\SymfonySerializer\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Storage\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\TemplateEngines\Twig\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemplateEngines\PlainPHP\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemporalBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemporalBridge\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Validators\Symfony\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Symfony\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\Validators\Laravel\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Laravel\Generator\Middlewares::class)

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
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\AnnotatedBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\CommandBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\CycleOrmBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\DatabaseBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\MigrationsBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\ScaffolderBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Cycle\Bootloader\SchemaBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Debug\Bootloader\DumperBootloader::class)
            ->assertBootloaderRegistered(\Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Nyholm\Bootloader\NyholmBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\CommandBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\HttpBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Views\TranslatedCacheBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\I18nBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Validation\Bootloader\ValidationBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Validator\Bootloader\ValidatorBootloader::class)

            // not registered bootloaders
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\DataGridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\DataGrid\Bootloader\GridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Events\Bootloader\EventsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\League\Event\Bootloader\EventBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\SendIt\Bootloader\MailerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\CacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\QueueBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Sapi\Bootloader\SapiBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Scheduler\Bootloader\SchedulerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Sentry\Bootloader\SentryReporterBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Serializer\Symfony\Bootloader\SerializerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Storage\Bootloader\StorageBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Distribution\Bootloader\DistributionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Twig\Bootloader\TwigBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\TemporalBridge\Bootloader\PrototypeBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Laravel\Bootloader\ValidatorBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Symfony\Bootloader\ValidatorBootloader::class)

            ->assertEnvNotDefined('QUEUE_CONNECTION', 'in-memory')
            ->assertEnvNotDefined('MAILER_FROM')
            ->assertFileNotExists('app/config/queue.php')

            ->assertCopied(
                'Application/Cli/resources/skeleton/app/src/Endpoint/Console/DoNothing.php',
                'app/src/Endpoint/Console/DoNothing.php'
            )
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
            ->assertReadmeContains('The settings for RoadRunner are in a file named .rr.yaml at the main folder of the app.');
    }

    public function testStemplerTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 1)
            ->run();

        $result->storeLog();

        $result
            ->assertPackageInstalled('spiral/stempler-bridge')
            ->assertPackageNotInstalled('spiral/twig-bridge')

            ->assertGeneratorProcessed(\Installer\Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\PlainPHP\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\Twig\Generator\Bootloaders::class)

            ->assertBootloaderRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Twig\Bootloader\TwigBootloader::class)

            ->assertCopied(
                'Module/TemplateEngines/Stempler/resources/config/views/stempler.php',
                'app/config/views/stempler.php'
            )
            ->assertCopied(
                'Module/TemplateEngines/Stempler/resources/views/layout/base.dark.php',
                'app/views/layout/base.dark.php'
            )
            ->assertCopied(
                'Module/TemplateEngines/Stempler/resources/views/home.dark.php',
                'app/views/home.dark.php'
            )
            ->assertNotCopied('Module/TemplateEngines/PlainPHP/resources/views/home.php')
            ->assertNotCopied('Module/TemplateEngines/Twig/resources/views/layout/base.twig')
            ->assertNotCopied('Module/TemplateEngines/Twig/resources/views/home.twig')
        ;
    }

    public function testTwigTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 2)
            ->run();

        $result->storeLog();

        $result
            ->assertPackageInstalled('spiral/twig-bridge')
            ->assertPackageNotInstalled('spiral/stempler-bridge')

            ->assertGeneratorProcessed(\Installer\Module\TemplateEngines\Twig\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\PlainPHP\Generator\Bootloaders::class)

            ->assertBootloaderRegistered(\Spiral\Twig\Bootloader\TwigBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)

            ->assertCopied(
                'Module/TemplateEngines/Twig/resources/views/layout/base.twig',
                'app/views/layout/base.twig'
            )
            ->assertCopied(
                'Module/TemplateEngines/Twig/resources/views/home.twig',
                'app/views/home.twig'
            )
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/config/views/stempler.php')
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/views/layout/base.dark.php')
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/views/home.dark.php')
            ->assertNotCopied('Module/TemplateEngines/PlainPHP/resources/views/home.php')
        ;
    }

    public function testPlainPHPTemplateEngine(): void
    {
        $result = $this
            ->install(Application::class)
            ->addAnswer(Module\TemplateEngines\Question::class, 3)
            ->run();

        $result->storeLog();

        $result
            ->assertPackageInstalled('spiral/views')
            ->assertPackageNotInstalled('spiral/stempler-bridge')
            ->assertPackageNotInstalled('spiral/twig-bridge')

            ->assertGeneratorProcessed(\Installer\Module\TemplateEngines\PlainPHP\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Module\TemplateEngines\Twig\Generator\Bootloaders::class)

            ->assertBootloaderRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Twig\Bootloader\TwigBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)

            ->assertCopied(
                'Module/TemplateEngines/PlainPHP/resources/views/home.php',
                'app/views/home.php'
            )
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/config/views/stempler.php')
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/views/layout/base.dark.php')
            ->assertNotCopied('Module/TemplateEngines/Stempler/resources/views/home.dark.php')
            ->assertNotCopied('Module/TemplateEngines/Twig/resources/views/layout/base.twig')
            ->assertNotCopied('Module/TemplateEngines/Twig/resources/views/home.twig')
        ;
    }
}
