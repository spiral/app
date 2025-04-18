<?php

declare(strict_types=1);

namespace Installer\Internal;

use Installer\Internal\Events\CopyEvent;

final class Resource
{
    public function __construct(
        private readonly string $root,
        private readonly array $directoriesMap = [],
    ) {}

    /**
     * Copy a resource file or directory to the project root.
     * If the resource is a directory, it will be copied recursively.
     *
     * @return \Generator<array-key, CopyEvent>
     */
    public function copy(string $resource, string $target): \Generator
    {
        foreach ($this->directoriesMap as $alias => $path) {
            if (\str_starts_with($resource, $alias)) {
                $resource = \str_replace($alias, $path, $resource);
            }
        }
        $copy = static function (string $source, string $destination) use (&$copy): \Generator {
            if (\is_dir($source)) {
                $handle = \opendir($source);
                while ($file = \readdir($handle)) {
                    if ($file !== '.' && $file !== '..') {
                        $destination = \rtrim($destination, '/');
                        if (\is_dir($source . '/' . $file)) {
                            if (!\is_dir($destination . '/' . $file)) {
                                \mkdir($destination . '/' . $file, 0775, true);
                            }
                            yield from $copy($source . '/' . $file, $destination . '/' . $file);
                        } else {
                            if (!\is_dir($destination)) {
                                \mkdir($destination, 0775, true);
                            }
                            \copy($source . '/' . $file, $destination . '/' . $file);

                            yield new CopyEvent($source . '/' . $file, $destination . '/' . $file);
                        }
                    }
                }
                \closedir($handle);
            } else {
                if (!\is_dir(\dirname($destination))) {
                    \mkdir(\dirname($destination), 0775, true);
                }

                \copy($source, $destination);
                yield new CopyEvent($source, $destination);
            }
        };

        yield from $copy($resource, $this->root . $target);
    }
}
