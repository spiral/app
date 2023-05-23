<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Console\IO;

final class Resource
{
    private const ENV_SAMPLE = '.env.sample';

    public function __construct(
        private readonly string $source,
        private readonly string $root,
        private readonly IO $io,
    ) {
    }

    /**
     * Create a new instance with a different source path.
     * It is useful when you want to copy resources from a specific module directory.
     */
    public function withSource(string $source): self
    {
        return new self($source, $this->root, $this->io);
    }

    /**
     * Copy a resource file or directory to the project root.
     * If the resource is a directory, it will be copied recursively.
     */
    public function copy(string $resource, string $target): self
    {
        $copy = function (string $source, string $destination) use (&$copy): void {
            if (\is_dir($source)) {
                $handle = \opendir($source);
                while ($file = \readdir($handle)) {
                    if ($file !== '.' && $file !== '..') {
                        $destination = \rtrim($destination, '/');
                        if (\is_dir($source . '/' . $file)) {
                            if (!\is_dir($destination . '/' . $file)) {
                                \mkdir($destination . '/' . $file, 0775, true);
                            }
                            $copy($source . '/' . $file, $destination . '/' . $file);
                        } else {
                            $this->writeInfo($destination . '/' . $file);
                            if (!\is_dir($destination)) {
                                \mkdir($destination, 0775, true);
                            }
                            \copy($source . '/' . $file, $destination . '/' . $file);
                        }
                    }
                }
                \closedir($handle);
            } else {
                if (!\is_dir(\dirname($destination))) {
                    \mkdir(\dirname($destination), 0775, true);
                }

                $this->writeInfo($destination);
                \copy($source, $destination);
            }
        };

        $copy($this->source . $resource, $this->root . $target);

        return $this;
    }

    /**
     * Copy the .env.sample file to the project root.
     */
    public function createEnv(): self
    {
        \copy($this->root . self::ENV_SAMPLE, $this->root . '.env');

        return $this;
    }

    private function writeInfo(string $destination): void
    {
        if (!$this->io->isVerbose()) {
            return;
        }

        $this->io->write(\sprintf('  - Copying <info>%s</info>', $destination));
    }
}
