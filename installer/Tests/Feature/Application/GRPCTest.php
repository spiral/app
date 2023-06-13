<?php

declare(strict_types=1);

namespace Feature\Application;

use App\Endpoint\Web\Middleware\LocaleSelector;
use Installer\Application\GRPC\Application;
use Installer\Module;
use Installer\Module\Console\Generator\Skeleton as ConsoleSkeleton;
use Installer\Module\Exception\Generator\Skeleton as ExceptionSkeleton;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Session\Middleware\SessionMiddleware;
use Tests\Feature\InstallerTestCase;

final class GRPCTest extends InstallerTestCase
{
    public function testDefaultInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->run();

        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // installed packages
            ->assertPackageInstalled('ext-sockets')
            ->assertPackageInstalled('ext-mbstring')
            ->assertPackageInstalled('ext-grpc')
            ->assertPackageInstalled('grpc/grpc')
            ->assertPackageInstalled('spiral/dumper')
            ->assertPackageInstalled('spiral/roadrunner-bridge')
            ->assertPackageInstalled('spiral/roadrunner-cli')
            ->assertPackageInstalled('spiral/nyholm-bridge')

            // not installed packages
            ->assertPackageNotInstalled('spiral-packages/yii-error-handler-bridge')
            ->assertPackageNotInstalled('spiral/cycle-bridge')
            ->assertPackageNotInstalled('doctrine/collections')
            ->assertPackageNotInstalled('spiral/validator')
            ->assertPackageNotInstalled('spiral/stempler-bridge')
            ->assertPackageNotInstalled('spiral/translator')
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
            ->assertGeneratorProcessed(\Installer\Application\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\GRPC\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\GRPC\Generator\Config::class)
            ->assertGeneratorProcessed(ConsoleSkeleton::class)
            ->assertGeneratorProcessed(ExceptionSkeleton::class)
            ->assertGeneratorProcessed(Module\Dumper\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Psr7Implementation\Nyholm\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\CacheConfig::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\Env::class)
            ->assertGeneratorProcessed(Module\RoadRunnerBridge\Common\Generator\QueueConfig::class)
            ->assertGeneratorProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)

            // not processed generators
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Cli\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\Validators\Spiral\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Spiral\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\ErrorHandler\Yii\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Collections::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Cache\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(Module\EventDispatcher\League\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Skeleton::class)
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
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\EncrypterBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\FiltersBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Bootloader\Security\GuardBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Debug\Bootloader\DumperBootloader::class)
            ->assertBootloaderRegistered(\Spiral\Nyholm\Bootloader\NyholmBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\CommandBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader::class)
            ->assertBootloaderRegistered(\Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader::class)

            // not registered bootloaders
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Views\TranslatedCacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\I18nBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Bootloader\ValidationBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validator\Bootloader\ValidatorBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\HttpBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\PaginationBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\AnnotatedBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\CommandBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\CycleOrmBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\DatabaseBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\MigrationsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\ScaffolderBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\SchemaBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CsrfBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\SessionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CookiesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\JsonPayloadsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\RouterBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\RoutesBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\AppBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\DataGridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\DataGrid\Bootloader\GridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Events\Bootloader\EventsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\League\Event\Bootloader\EventBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\SendIt\Bootloader\MailerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\CacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\QueueBootloader::class)
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
                'Application/Common/resources/app/config/scaffolder.php',
                'app/config/scaffolder.php'
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Bootloader/LoggingBootloader.php',
                'app/src/Application/Bootloader/LoggingBootloader.php'
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Bootloader/ExceptionHandlerBootloader.php',
                'app/src/Application/Bootloader/ExceptionHandlerBootloader.php'
            )
            ->assertCopied(
                'Application/Common/resources/app/src/Application/Kernel.php',
                'app/src/Application/Kernel.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/proto/service.proto',
                'proto/service.proto'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Ping/PingServiceClient.php',
                'generated/Ping/PingServiceClient.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Ping/PingResponse.php',
                'generated/Ping/PingResponse.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Ping/PingRequest.php',
                'generated/Ping/PingRequest.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Ping/PingServiceInterface.php',
                'generated/Ping/PingServiceInterface.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Ping/GPBMetadata/Service.php',
                'generated/Ping/GPBMetadata/Service.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Config/GRPCServicesConfig.php',
                'generated/Config/GRPCServicesConfig.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/generated/Bootloader/ServiceBootloader.php',
                'generated/Bootloader/ServiceBootloader.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/app/src/Endpoint/RPC/PingService.php',
                'app/src/Endpoint/RPC/PingService.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/grpc/app/src/Endpoint/Console/PingCommand.php',
                'app/src/Endpoint/Console/PingCommand.php'
            )
            ->assertCopied(
                'Module/RoadRunnerBridge/resources/config/grpc_services.php',
                '/app/config/grpc.php'
            )
            ->assertCopied(
                'Module/Exception/resources/app.php',
                'app.php'
            )
            ->assertCopied(
                'Module/Exception/resources/app/src/Application/Exception/Handler.php',
                'app/src/Application/Exception/Handler.php'
            )
            ->assertCopied(
                'Application/Cli/resources/skeleton/app/src/Endpoint/Console/DoNothing.php',
                'app/src/Endpoint/Console/DoNothing.php'
            )
            ->assertNotCopied(
                'Module/Translator/resources/locale/ru/messages.en.php',
                'app/locale/ru/messages.en.php'
            )
            ->assertNotCopied(
                'Application/Web/Generator/resources/ViewRenderer.php',
                'app/src/Application/Exception/Renderer/ViewRenderer.php'
            )

            ->assertMiddlewareNotRegistered(ErrorHandlerMiddleware::class)
            ->assertMiddlewareNotRegistered(HttpCollector::class, 'web')
            ->assertMiddlewareNotRegistered(SessionMiddleware::class, 'web')
            ->assertMiddlewareNotRegistered(LocaleSelector::class)
            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!')
            ->assertCommandExecuted('rr download-protoc-binary')
            ->assertReadmeContains('The settings for RoadRunner are in a file named .rr.yaml at the main folder of the app.');
    }
}
