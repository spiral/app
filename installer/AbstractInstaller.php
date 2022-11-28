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
     * @var ApplicationInterface[]
     */
    protected array $config;

    protected string $projectRoot;

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

        // Parse the composer.json
        $this->parseComposerDefinition($composerFile);

        $this->config = require __DIR__ . '/config.php';
    }

    /**
     * @throws ParsingException
     */
    private function parseComposerDefinition(string $composerFile): void
    {
        $composerJson = new JsonFile($composerFile);
        $this->composerDefinition = $composerJson->read();
    }
}
