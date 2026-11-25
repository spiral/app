<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Installer;

use Composer\Package\RootPackage;
use Installer\Internal\Configurator\ResourceQueue;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Installer\Answers;
use Installer\Internal\Installer\ApplicationState;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorageInterface;
use Installer\Internal\Installer\IOInteractions;
use Installer\Module\EventDispatcher\Question as EventDispatcherQuestion;
use Installer\Module\Validators\Question as ValidatorsQuestion;
use Tests\TestCase;

final class IOInteractionsTest extends TestCase
{
    private IOInterface|\Mockery\MockInterface $io;

    public function testPresetApplicationType(): void
    {
        $this->io->shouldNotReceive('ask');

        $this->assertSame(2, $this->interactions(new Answers('grpc'))->requestApplicationType());
    }

    public function testUnknownApplicationType(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('unknown application "Desktop"; available: Web, Cli, gRPC');

        $this->interactions(new Answers('Desktop'))->requestApplicationType();
    }

    public function testPresetAnswersReplacePrompts(): void
    {
        $asked = [];
        $this->io->shouldReceive('ask')->andReturnUsing(
            static function (string $question, string|int|null $default) use (&$asked): string|int|null {
                $asked[] = $question;

                return $default;
            },
        );

        $answers = Answers::fromJson('{"questions": {"EventDispatcher": 1, "Validators": 0}}');
        $application = $this->getConfig()->getApplication(0);

        $chosen = [];
        foreach ($this->interactions($answers)->promptForOptionalPackages($application) as $question => $option) {
            $chosen[$question::class] = $option->getName();
        }

        $this->assertArrayHasKey(EventDispatcherQuestion::class, $chosen);
        $this->assertArrayNotHasKey(ValidatorsQuestion::class, $chosen);
        foreach ($asked as $question) {
            $this->assertStringNotContainsString((new EventDispatcherQuestion())->getQuestion(), $question);
            $this->assertStringNotContainsString((new ValidatorsQuestion())->getQuestion(), $question);
        }
    }

    public function testPresetAnswerOutOfRange(): void
    {
        $this->io->shouldReceive('ask')->andReturnUsing(
            static fn(string $question, string|int|null $default): string|int|null => $default,
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('the question "eventdispatcher" has no option 9');

        $answers = Answers::fromJson('{"questions": {"EventDispatcher": 9}}');
        \iterator_to_array(
            $this->interactions($answers)->promptForOptionalPackages($this->getConfig()->getApplication(0)),
            false,
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->io = \Mockery::mock(IOInterface::class);
        $this->io->shouldReceive('write');
        $this->io->shouldReceive('error');
    }

    private function interactions(Answers $answers): IOInteractions
    {
        $storage = \Mockery::mock(ComposerStorageInterface::class);
        $storage->shouldReceive('read')->andReturn($this->getComposerJson());

        $config = $this->getConfig();
        $state = new ApplicationState(
            $this->getAppPath(),
            new ComposerFile($storage, new RootPackage('spiral/app', '1.0.0', '1.0.0'), $config),
            new ResourceQueue(directoriesMap: $config->getDirectories()),
        );

        return new IOInteractions($this->io, $config, $state, $answers);
    }
}
