<?php

declare(strict_types=1);

namespace Installer;

use Composer\IO\IOInterface;

final class Resource
{
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
                        if (\is_dir($source . '/' . $file)) {
                            if (!\is_dir($destination . '/' . $file)) {
                                \mkdir($destination . '/' . $file, 0775, true);
                            }
                            $copy($source . '/' . $file, $destination . '/' . $file);
                        } else {
                            $this->io?->write(
                                \sprintf(
                                    '  - Copying <info>%s</info>',
                                    \str_replace('\\', '/', $destination) . '/' . $file
                                )
                            );
                            if (!\is_dir($destination)) {
                                \mkdir($destination, 0775, true);
                            }
                            \copy($source . '/' . $file, $destination . '/' . $file);
                        }
                    }
                }
                \closedir($handle);
            } else {
                $this->io?->write(\sprintf('  - Copying <info>%s</info>', \str_replace('\\', '/', $destination)));
                \copy($source, $destination);
            }
        };

        $copy($this->source . $resource, $this->root . $target);
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
