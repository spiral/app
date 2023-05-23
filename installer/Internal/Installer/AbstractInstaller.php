<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Factory;
use Composer\IO\IOInterface;
use Installer\Internal\Console\IO;
use Installer\Internal\Resource;
use Seld\JsonLint\ParsingException;

abstract class AbstractInstaller
{
    protected array $composerDefinition;

    /**
     * @var array<int|string, ApplicationInterface|list<string>>
     */
    protected array $config;
    protected string $projectRoot;
    protected Resource $resource;

    protected readonly IO $io;
    protected ApplicationState $applicationState;
    protected readonly string $composerFile;

    /**
     * @throws ParsingException
     */
    public function __construct(
        IOInterface $io,
        ?string $projectRoot = null
    ) {
        $this->io = new IO($io);

        $this->composerFile = Factory::getComposerFile();

        $this->projectRoot = $projectRoot ?? \str_replace('\\', '/', \realpath(\dirname($composerFile)));
        $this->projectRoot = \rtrim($this->projectRoot, '/\\') . '/';

        $this->config = require __DIR__ . '/config.php';

        $this->resource = new Resource(\realpath(__DIR__) . '/Resources/', $this->projectRoot);
    }
}
