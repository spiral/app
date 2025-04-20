<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Installer\Internal\Config;
use Installer\Internal\Console\IOInterface;
use Installer\Internal\Resource;
use Seld\JsonLint\ParsingException;

abstract class AbstractInstaller
{
    protected Config $config;
    protected string $projectRoot;
    protected Resource $resource;
    protected ApplicationState $applicationState;

    /**
     * @throws ParsingException
     */
    public function __construct(
        protected readonly IOInterface $io,
        protected readonly string $composerFile,
        ?string $projectRoot = null,
    ) {
        $this->projectRoot = $projectRoot ?? \str_replace('\\', '/', \realpath(\dirname($this->composerFile)));
        $this->projectRoot = \rtrim($this->projectRoot, '/\\') . '/';
        $this->config = new Config(__DIR__ . '/../../config.php');
        $this->resource = new Resource(
            $this->projectRoot,
            $this->config->getDirectories(),
        );
    }
}
