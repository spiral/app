<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Env;

use Installer\Internal\Generator\Env\EnvGroup;
use Tests\TestCase;

final class EnvGroupTest extends TestCase
{
    public function testRenderWithValuesWithoutComment(): void
    {
        $group = new EnvGroup(values: [
            'FOO' => 'BAR',
            'BAR' => 'BAZ',
        ]);

        $this->assertSame(
            <<<ENV

            FOO=BAR
            BAR=BAZ
            ENV,
            (string)$group
        );
    }

    public function testRenderWithValuesAndComment(): void
    {
        $group = new EnvGroup(values: [
            'FOO' => 'BAR',
            'BAR' => 'BAZ',
        ], comment: 'Some group');

        $this->assertSame(
            <<<ENV

            # Some group
            FOO=BAR
            BAR=BAZ
            ENV,
            (string)$group
        );
    }

    public function testAddValue(): void
    {
        $group = new EnvGroup(comment: 'Some group');
        $group->addValue('FOO', 'BAR');

        $this->assertSame(
            <<<ENV

            # Some group
            FOO=BAR
            ENV,
            (string)$group
        );
    }

    public function testHasValue(): void
    {
        $group = new EnvGroup(comment: 'Some group');
        $this->assertFalse($group->hasValue('FOO'));
        $group->addValue('FOO', 'BAR');
        $this->assertTrue($group->hasValue('FOO'));
    }

    public function testRenderWithoutValues(): void
    {
        $group = new EnvGroup(comment: 'Some group');

        $this->assertSame('', (string)$group);
    }

    public function testSupportedValueTypes(): void
    {
        $group = new EnvGroup([
            'string' => 'BAR',
            'int' => 123,
            'float' => 123.1,
            'bool' => true,
            'null' => null,
            'array' => ['foo', 'bar'],
            'stringable' => new class() implements \Stringable {
                public function __toString(): string
                {
                    return 'stringable';
                }
            },
            'json' => new class() implements \JsonSerializable {
                public function jsonSerialize(): array
                {
                    return ['foo' => 'bar'];
                }
            },
        ]);

        $this->assertSame(
            <<<ENV

            string=BAR
            int=123
            float=123.1
            bool=true
            null=null
            array=foo,bar
            stringable=stringable
            json={"foo":"bar"}
            ENV,
            (string)$group
        );
    }


    public function testRenderEmptyGroup(): void
    {
        $group = new EnvGroup();

        $this->assertSame('', (string)$group);
    }
}
