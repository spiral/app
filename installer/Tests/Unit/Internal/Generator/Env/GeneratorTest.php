<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Generator\Env;

use Installer\Internal\Generator\Env\Generator;
use Mockery\MockInterface;
use Spiral\Files\FilesInterface;
use Tests\TestCase;

final class GeneratorTest extends TestCase
{
    private Generator $generator;
    private FilesInterface|MockInterface $files;

    public function testAddGroupWithExistsKey(): void
    {
        $this->generator->addGroup(values: [
            'FOO' => 'BAR',
        ]);

        $this->generator->addGroup(values: [
            'FOO' => 'BAZ',
        ], comment: 'Some group');

        $this->assertSame(
            <<<ENV
            FOO=BAZ

            ENV,
            (string) $this->generator,
        );

        $this->generator->addValue('FOO', 'BAF');

        $this->assertSame(
            <<<ENV
            FOO=BAF

            ENV,
            (string) $this->generator,
        );
    }

    public function testAddGroup(): void
    {
        $this->generator->addGroup(values: [
            'FOO' => 'BAR',
        ]);

        $this->generator->addGroup(values: [
            'BAZ' => 'BAR',
        ], comment: 'Some group');

        $this->generator->addGroup(values: [], comment: 'Some group1');

        $this->assertSame(
            <<<ENV
            FOO=BAR

            # Some group
            BAZ=BAR

            ENV,
            (string) $this->generator,
        );

        $this->generator->addGroup(
            values: [
                'BAF' => 'BAR',
                'BAN' => 'BAR',
            ],
            comment: 'Some group2',
            priority: -1,
        );

        $this->assertSame(
            <<<ENV
            # Some group2
            BAF=BAR
            BAN=BAR

            FOO=BAR

            # Some group
            BAZ=BAR

            ENV,
            (string) $this->generator,
        );
    }

    public function testAddValue(): void
    {
        $this->generator->addGroup(values: [
            'FOO' => 'BAR',
            'BAZ' => 'BAR',
        ], comment: 'Some group');

        $this->generator->addValue('BAN', 'BAZ');

        $this->assertSame(
            <<<ENV
            # Some group
            FOO=BAR
            BAZ=BAR

            BAN=BAZ

            ENV,
            (string) $this->generator,
        );

        $this->generator->addValue('FOO', 'BAZ');

        $this->assertSame(
            <<<ENV
            # Some group
            FOO=BAZ
            BAZ=BAR

            BAN=BAZ

            ENV,
            (string) $this->generator,
        );
    }

    public function testPersist(): void
    {
        $this->generator->addGroup(values: [
            'FOO' => 'BAR',
        ]);

        $this->files->shouldReceive('write')
            ->once()
            ->with(
                '/var/www/.env.sample',
                <<<ENV
                FOO=BAR

                ENV,
                FilesInterface::RUNTIME,
            );

        $this->files->shouldReceive('copy')
            ->once()
            ->with('/var/www/.env.sample', '/var/www/.env');

        $this->generator->persist();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = \Mockery::mock(FilesInterface::class);

        $this->generator = new Generator(
            projectRoot: '/var/www/',
            files: $this->files,
        );
    }
}
