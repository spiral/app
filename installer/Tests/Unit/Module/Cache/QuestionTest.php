<?php

declare(strict_types=1);

namespace Tests\Unit\Module\Cache;

use Installer\Module\Cache\Question;
use Tests\TestCase;

final class QuestionTest extends TestCase
{
    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->question = new Question();
    }

    public function testGetsQuestion(): void
    {
        $this->assertNotEmpty($this->question->getQuestion());
    }

    public function testShouldBeOptional(): void
    {
        $this->assertFalse($this->question->isRequired());
    }

    public function testAmountOfOptions(): void
    {
        $this->assertCount(1, $this->question->getOptions());
    }
}
