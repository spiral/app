<?php

declare(strict_types=1);

namespace Feature\Application;

use Installer\Application\Cli\Application;
use Installer\Module;
use Tests\Feature\InstallerTestCase;

final class ConsoleTest extends InstallerTestCase
{
    public function testDefaultInstall(): void
    {
        $result = $this
            ->install(Application::class)
            ->withSkeleton()
            ->run();

        // Store log for debugging in application directory
        $result->storeLog();

        $result
            // deleted files and directories
            ->assertDeleted('LICENSE')
            ->assertDeleted('.github')

            // installed packages
            ->assertPackageInstalled('ext-mbstring')
            ->assertPackageInstalled('spiral/dumper')
            ->assertPackageInstalled('spiral/roadrunner-cli')
            ->assertPackageInstalled('spiral/nyholm-bridge')

            // not installed packages
            ->assertPackageNotInstalled('ext-sockets')
            ->assertPackageNotInstalled('ext-grpc')
            ->assertPackageNotInstalled('grpc/grpc')
            ->assertPackageNotInstalled('spiral/roadrunner-bridge')
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
            ->assertPackageNotInstalled('spiral/views')
            ->assertPackageNotInstalled('spiral-packages/yii-error-handler-bridge')
            ->assertPackageNotInstalled('spiral/cycle-bridge')
            ->assertPackageNotInstalled('doctrine/collections')
            ->assertPackageNotInstalled('spiral/validator')
            ->assertPackageNotInstalled('spiral/stempler-bridge')
            ->assertPackageNotInstalled('spiral/translator')

            // processed generators
            ->assertGeneratorProcessed(\Installer\Application\Cli\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(\Installer\Application\Cli\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\Console\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\Exception\Generator\Skeleton::class)
            ->assertGeneratorProcessed(Module\Dumper\Generator\Bootloaders::class)
            ->assertGeneratorProcessed(Module\Psr7Implementation\Nyholm\Generator\Bootloaders::class)

            // not processed generators
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\ViewRenderer::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Env::class)
            ->assertGeneratorNotProcessed(\Installer\Application\Web\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(\Installer\Application\GRPC\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Cache\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Collections::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(Module\CycleBridge\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\DataGridBridge\Generator\Interceptors::class)
            ->assertGeneratorNotProcessed(Module\ErrorHandler\Yii\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\EventDispatcher\League\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Mailer\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Config::class)
            ->assertGeneratorNotProcessed(Module\Queue\Generator\Skeleton::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\Common\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\Common\Generator\CacheConfig::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\Common\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\Common\Generator\QueueConfig::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\GRPC\Generator\GRPCBootloader::class)
            ->assertGeneratorNotProcessed(Module\RoadRunnerBridge\GRPC\Generator\GRPCSkeleton::class)
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
            ->assertGeneratorNotProcessed(Module\TemplateEngines\Stempler\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemplateEngines\Twig\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemporalBridge\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\TemporalBridge\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Env::class)
            ->assertGeneratorNotProcessed(Module\Translator\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\Validators\Laravel\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Laravel\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\Validators\Spiral\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Spiral\Generator\Middlewares::class)
            ->assertGeneratorNotProcessed(Module\Validators\Symfony\Generator\Bootloaders::class)
            ->assertGeneratorNotProcessed(Module\Validators\Symfony\Generator\Middlewares::class)

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
            ->assertBootloaderRegistered(\Spiral\Nyholm\Bootloader\NyholmBootloader::class)

            // not registered bootloaders
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\AppBootloader::class)
            ->assertBootloaderNotRegistered(\App\Application\Bootloader\RoutesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\AnnotatedBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\CommandBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\CycleOrmBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\DatabaseBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\DataGridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Security\FiltersBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Security\GuardBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\RouterBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\JsonPayloadsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CookiesBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\SessionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\CsrfBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Http\PaginationBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\MigrationsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\ScaffolderBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Cycle\Bootloader\SchemaBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\DataGrid\Bootloader\GridBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Events\Bootloader\EventsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\League\Event\Bootloader\EventBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\SendIt\Bootloader\MailerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\CacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\CommandBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\HttpBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\LoggerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\QueueBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\RoadRunnerBridge\Bootloader\GRPCBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Sapi\Bootloader\SapiBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Scheduler\Bootloader\SchedulerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Sentry\Bootloader\SentryReporterBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\SerializableClosure\Bootloader\SerializableClosureBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Serializer\Symfony\Bootloader\SerializerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Storage\Bootloader\StorageBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Distribution\Bootloader\DistributionBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Views\Bootloader\ViewsBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Stempler\Bootloader\StemplerBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Twig\Bootloader\TwigBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\TemporalBridge\Bootloader\PrototypeBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\I18nBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Bootloader\Views\TranslatedCacheBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Bootloader\ValidationBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Laravel\Bootloader\ValidatorBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validator\Bootloader\ValidatorBootloader::class)
            ->assertBootloaderNotRegistered(\Spiral\Validation\Symfony\Bootloader\ValidatorBootloader::class)

            ->assertCopied(
                'Application/Cli/resources/skeleton/app/src/Endpoint/Console/DoNothing.php',
                'app/src/Endpoint/Console/DoNothing.php'
            )
            ->assertCopied(
                'Module/Exception/resources/app.php',
                'app.php'
            )
            ->assertCopied(
                'Module/Exception/resources/app/src/Application/Exception/Handler.php',
                'app/src/Application/Exception/Handler.php'
            )

            ->assertMessageShown('Removing Installer from composer.json ...')
            ->assertMessageShown('Installation complete!');
    }
}
