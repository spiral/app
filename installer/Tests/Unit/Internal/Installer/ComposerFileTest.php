<?php

declare(strict_types=1);

namespace Tests\Unit\Internal\Installer;

use Composer\Package\RootPackage;
use Installer\Application\ComposerPackages;
use Installer\Internal\Console\Output;
use Installer\Internal\Installer\ComposerFile;
use Installer\Internal\Installer\ComposerStorageInterface;
use Installer\Internal\Package;
use Installer\Internal\Question\Option\BooleanOption;
use Installer\Module\Cache\Question;
use Tests\TestCase;

final class ComposerFileTest extends TestCase
{
    private ComposerStorageInterface|\Mockery\MockInterface $storage;
    private ComposerFile $composer;
    private RootPackage $package;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = \Mockery::mock(ComposerStorageInterface::class);

        $this->storage
            ->shouldReceive('read')
            ->once()
            ->andReturn($this->getComposerJson());

        $this->composer = new ComposerFile(
            $this->storage,
            $this->package = new RootPackage('spiral/app', '1.0.0', '1.0.0'),
            $this->getConfig()
        );
    }

    public function testSetApplicationType(): void
    {
        $this->composer->setApplicationType($type = 1);

        $this->storage->shouldReceive('write')
            ->once()
            ->withArgs(static function (array $json) use ($type): bool {
                return $json['extra']['spiral']['application-type'] === $type;
            });

        $this->assertOutputContains(
            $this->composer->persist([], []),
            [
                '[comment] Storing composer.json ...',
                '[success] composer.json file updated.',
            ]
        );

        $this->assertSame($type, $this->composer->getApplicationType());
    }

    public function testAddsQuestionAnswer(): void
    {
        $this->composer->addQuestionAnswer(new Question(), new BooleanOption('Yes'));

        $this->storage->shouldReceive('write')
            ->once()
            ->withArgs(static function (array $json): bool {
                return $json['extra']['spiral']['options'][Question::class] === true;
            });

        $this->assertOutputContains(
            $this->composer->persist([], []),
            []
        );
    }

    public function testAddsPackage(): void
    {
        $this->composer->addPackage(
            new Package(
                ComposerPackages::ExtGRPC
            )
        );

        $this->composer->addPackage(
            new Package(
                ComposerPackages::Dumper
            )
        );

        $this->storage->shouldReceive('write')
            ->once()
            ->withArgs(static function (array $json): bool {
                return
                    $json['require']['ext-grpc'] === '*'
                    && $json['require-dev']['spiral/dumper'] === '^3.0'
                    && \in_array('ext-grpc', $json['extra']['spiral']['packages'])
                    && \in_array('spiral/dumper', $json['extra']['spiral']['packages']);
            });

        $this->assertOutputContains(
            $this->composer->persist([], []),
            []
        );

        $this->assertSame(['ext-grpc', 'spiral/dumper'], $this->composer->getInstalledPackages());
    }

    private function assertOutputContains(\Generator $output, array $lines): void
    {
        $output = \array_map(
            static fn(Output $output): string => (string)$output,
            \iterator_to_array($output)
        );

        foreach ($lines as $line) {
            $this->assertContains($line, $output);
        }
    }
}
