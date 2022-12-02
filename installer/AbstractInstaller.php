<?php

declare(strict_types=1);

namespace Installer;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Installer\Application\ApplicationInterface;
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

    /**
     * @throws ParsingException
     */
    public function __construct(
        protected readonly IOInterface $io,
        ?string $projectRoot = null
    ) {
        $composerFile = Factory::getComposerFile();

        $this->projectRoot = $projectRoot ?? \realpath(\dirname($composerFile));
        $this->projectRoot = \rtrim($this->projectRoot, '/\\') . '/';

        $composerJson = new JsonFile($composerFile);
        $this->composerDefinition = $composerJson->read();

        $this->config = require __DIR__ . '/config.php';

        $this->resource = new Resource(\realpath(__DIR__) . '/Resources/', $this->projectRoot, $io);
    }
}
