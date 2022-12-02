<?php

declare(strict_types=1);

namespace Installer;

use Composer\IO\IOInterface;

final class Resource
{
    private const ENV_SAMPLE = '.env.sample';

    public function __construct(
        private readonly string $source,
        private readonly string $root,
        private readonly ?IOInterface $io = null
    ) {
    }

    public function copy(string $resource, string $target): void
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
                $this->writeInfo($destination);
                \copy($source, $destination);
            }
        };

        $copy($this->source . $resource, $this->root . $target);
    }

    public function createEnv(): void
    {
        $this->writeInfo($this->root . '.env');

        \copy($this->root . self::ENV_SAMPLE, $this->root . '.env');
    }

    public function getSource(): string
    {
        return $this->source;
    }

    private function writeInfo(string $destination): void
    {
        $this->io?->write(\sprintf('  - Copying <info>%s</info>', $destination));
    }
}
