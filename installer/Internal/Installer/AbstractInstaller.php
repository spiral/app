<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Factory;
use Installer\Internal\ApplicationInterface;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Resource;
use Seld\JsonLint\ParsingException;

abstract class AbstractInstaller
{
    /**
     * @var array<int|string, ApplicationInterface|list<string>>
     */
    protected array $config;
    protected string $projectRoot;
    protected Resource $resource;
    protected ApplicationState $applicationState;
    protected readonly string $composerFile;

    /**
     * @throws ParsingException
     */
    public function __construct(
        protected readonly IOInterface $io,
        ?string $projectRoot = null
    ) {
        $this->composerFile = Factory::getComposerFile();

        $this->projectRoot = $projectRoot ?? \str_replace('\\', '/', \realpath(\dirname($this->composerFile)));
        $this->projectRoot = \rtrim($this->projectRoot, '/\\') . '/';

        $this->config = require __DIR__ . '/../../config.php';
        $this->resource = new Resource(\realpath(__DIR__) . '/Resources/');
    }
}
