<?php

declare(strict_types=1);

namespace Installer\Internal\Installer;

use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
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
    protected JsonFile $composerJson;
    protected readonly IO $io;

    /**
     * @throws ParsingException
     */
    public function __construct(
        IOInterface $io,
        ?string $projectRoot = null
    ) {
        $this->io = new IO($io);

        $composerFile = Factory::getComposerFile();

        $this->projectRoot = $projectRoot ?? \str_replace('\\', '/', \realpath(\dirname($composerFile)));
        $this->projectRoot = \rtrim($this->projectRoot, '/\\') . '/';

        $this->composerJson = new JsonFile($composerFile);
        $this->composerDefinition = $this->composerJson->read();

        $this->config = require __DIR__ . '/config.php';

        $this->resource = new Resource(\realpath(__DIR__) . '/Resources/', $this->projectRoot, $this->io);
    }

    protected function recursiveRmdir(string $directory): void
    {
        if (!\is_dir($directory)) {
            return;
        }

        $rdi = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS);
        $rii = new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($rii as $filename => $fileInfo) {
            if ($fileInfo->isDir()) {
                \rmdir($filename);
                continue;
            }
            \unlink($filename);
        }
        \rmdir($directory);
    }
}
