<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Console\Command\Database;
use App\Console\Command\CycleOrm;
use App\Console\Command\Migrate;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\ORMInterface;
use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\ConsoleBootloader;
use Spiral\Command\Router;
use Spiral\Command\Translator;
use Spiral\Command\Views;
use Spiral\Command\GRPC;
use Spiral\Command\Encrypter;
use Spiral\Console\Sequence\RuntimeDirectory;
use Spiral\Core\Container;
use Spiral\Console;
use Spiral\Encrypter\EncryptionInterface;
use Spiral\Files\FilesInterface;
use Cycle\Migrations\Migrator;
use Spiral\Router\RouterInterface;
use Spiral\Translator\Config\TranslatorConfig;
use Spiral\Translator\TranslatorInterface;
use Spiral\Views\ViewsInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CommandBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class,
        MigrationsBootloader::class,
    ];

    public function boot(ConsoleBootloader $console, Container $container): void
    {
        $console->addCommand(Console\Command\ConfigureCommand::class);
        $console->addCommand(Console\Command\UpdateCommand::class);

        $console->addConfigureSequence(
            [RuntimeDirectory::class, 'ensure'],
            '<fg=magenta>[runtime]</fg=magenta> <fg=cyan>verify `runtime` directory access</fg=cyan>'
        );

        $this->configureExtensions($console, $container);
    }

    private function configureExtensions(ConsoleBootloader $console, Container $container): void
    {
        if ($container->has(DatabaseProviderInterface::class)) {
            $this->configureDatabase($console);
        }

        if ($container->has(ORMInterface::class)) {
            $this->configureCycle($console, $container);
        }

        if ($container->has(TranslatorInterface::class)) {
            $this->configureTranslator($console);
        }

        if ($container->has(ViewsInterface::class)) {
            $this->configureViews($console);
        }

        if ($container->has(Migrator::class)) {
            $this->configureMigrations($console);
        }

        if ($container->has(InvokerInterface::class)) {
            $this->configureGRPC($console);
        }

        if ($container->has(EncryptionInterface::class)) {
            $this->configureEncrypter($console);
        }

        if ($container->has(RouterInterface::class)) {
            $console->addCommand(Router\ListCommand::class);
        }
    }

    private function configureDatabase(ConsoleBootloader $console): void
    {
        $console->addCommand(Database\ListCommand::class);
        $console->addCommand(Database\TableCommand::class);
    }

    private function configureCycle(ConsoleBootloader $console, ContainerInterface $container): void
    {
        $console->addCommand(CycleOrm\UpdateCommand::class);
        $console->addCommand(CycleOrm\RenderCommand::class);

        $console->addUpdateSequence(
            'cycle',
            '<fg=magenta>[cycle]</fg=magenta> <fg=cyan>update Cycle schema...</fg=cyan>'
        );

        $console->addCommand(CycleOrm\SyncCommand::class);

        if ($container->has(Migrator::class)) {
            $console->addCommand(CycleOrm\MigrateCommand::class);
        }
    }

    private function configureTranslator(ConsoleBootloader $console): void
    {
        $console->addCommand(Translator\IndexCommand::class);
        $console->addCommand(Translator\ExportCommand::class);
        $console->addCommand(Translator\ResetCommand::class);

        $console->addConfigureSequence(
            function (FilesInterface $files, TranslatorConfig $config, OutputInterface $output): void {
                $files->ensureDirectory($config->getLocaleDirectory($config->getDefaultLocale()));
                $output->writeln('<info>The default locale directory has been ensured.</info>');
            },
            '<fg=magenta>[i18n]</fg=magenta> <fg=cyan>ensure default locale directory...</fg=cyan>'
        );

        $console->addConfigureSequence(
            'i18n:index',
            '<fg=magenta>[i18n]</fg=magenta> <fg=cyan>scan translator function and [[values]] usage...</fg=cyan>'
        );
    }

    private function configureViews(ConsoleBootloader $console): void
    {
        $console->addCommand(Views\ResetCommand::class);
        $console->addCommand(Views\CompileCommand::class);

        $console->addConfigureSequence(
            'views:compile',
            '<fg=magenta>[views]</fg=magenta> <fg=cyan>warm up view cache...</fg=cyan>'
        );
    }

    private function configureMigrations(ConsoleBootloader $console): void
    {
        $console->addCommand(Migrate\InitCommand::class);
        $console->addCommand(Migrate\StatusCommand::class);
        $console->addCommand(Migrate\MigrateCommand::class);
        $console->addCommand(Migrate\RollbackCommand::class);
        $console->addCommand(Migrate\ReplayCommand::class);
    }

    private function configureGRPC(ConsoleBootloader $console): void
    {
        $console->addCommand(GRPC\GenerateCommand::class);
        $console->addCommand(GRPC\ListCommand::class);
    }

    private function configureEncrypter(ConsoleBootloader $console): void
    {
        $console->addCommand(Encrypter\KeyCommand::class);
    }

}
