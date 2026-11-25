<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Installer;

use Installer\Application\ApplicationSkeleton;
use Installer\Internal\Installer\Answers;
use Installer\Module\CycleBridge\CollectionsQuestion;
use Installer\Module\EventDispatcher\Question as EventDispatcherQuestion;
use Installer\Module\RoadRunnerBridge\RoadRunnerMetrics;
use Installer\Module\Validators\Question as ValidatorsQuestion;
use Tests\TestCase;

final class AnswersTest extends TestCase
{
    public function testKey(): void
    {
        $this->assertSame('eventdispatcher', Answers::key(new EventDispatcherQuestion()));
        $this->assertSame('cyclebridge/collectionsquestion', Answers::key(new CollectionsQuestion()));
        $this->assertSame('roadrunnerbridge/roadrunnermetrics', Answers::key(new RoadRunnerMetrics()));
        $this->assertSame('applicationskeleton', Answers::key(new ApplicationSkeleton()));
    }

    public function testFromJson(): void
    {
        $answers = Answers::fromJson(
            '{"application": "Web", "questions": {"EventDispatcher": 1, "CycleBridge\\\\CollectionsQuestion": 2, "Validators": false}}',
        );

        $this->assertSame('Web', $answers->application);
        $this->assertSame(1, $answers->get(new EventDispatcherQuestion()));
        $this->assertSame(2, $answers->get(new CollectionsQuestion()));
        $this->assertSame(0, $answers->get(new ValidatorsQuestion()));
        $this->assertNull($answers->get(new RoadRunnerMetrics()));
    }

    public function testSlashSeparatedKey(): void
    {
        $answers = Answers::fromJson('{"questions": {"cyclebridge/CollectionsQuestion": 1}}');

        $this->assertNull($answers->application);
        $this->assertSame(1, $answers->get(new CollectionsQuestion()));
    }

    public function testInvalidJson(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('SPIRAL_INSTALLER_ANSWERS is not valid JSON');

        Answers::fromJson('{application: Web}');
    }

    public function testAnswerMustBeOptionNumber(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('the answer to "EventDispatcher" must be an option number');

        Answers::fromJson('{"questions": {"EventDispatcher": "League"}}');
    }

    public function testUnknownQuestion(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('unknown questions eventdispather');

        Answers::fromJson('{"questions": {"EventDispather": 1}}')
            ->assertKnown([new EventDispatcherQuestion(), new ValidatorsQuestion()]);
    }
}
