<?php

declare(strict_types=1);

namespace Installer\Internal;

final class Resource
{
    private const ENV_SAMPLE = '.env.sample';

    public function __construct(
        private readonly string $root,
    ) {
    }

    /**
     * Copy a resource file or directory to the project root.
     * If the resource is a directory, it will be copied recursively.
     */
    public function copy(string $resource, string $target): \Generator
    {
        $copy = function (string $source, string $destination) use (&$copy): \Generator {
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
                            yield $destination . '/' . $file;
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

                yield $destination;
                \copy($source, $destination);
            }
        };

        yield from $copy($resource, $this->root . $target);
    }

    /**
     * Copy the .env.sample file to the project root.
     */
    public function createEnv(): void
    {
        \copy($this->root . self::ENV_SAMPLE, $this->root . '.env');
    }
}
