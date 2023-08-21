<?php

declare(strict_types=1);

namespace Tests;

use Composer\IO\IOInterface;
use Spiral\Files\FilesInterface;

final class FakeFileSystem implements FilesInterface
{
    public function __construct(
        private readonly IOInterface $io,
        private readonly FilesInterface $files,
    ) {
    }

    public function ensureDirectory(string $directory, int $mode = null): bool
    {
        return $this->files->ensureDirectory($directory, $mode);
    }

    public function read(string $filename): string
    {
        return $this->files->read($filename);
    }

    public function write(
        string $filename,
        string $data,
        int $mode = null,
        bool $ensureDirectory = false
    ): bool {
        $this->io->write(\sprintf('Writing to %s', $filename));
        $this->io->write($data);

        return $this->files->write($filename, $data, $mode, $ensureDirectory);
    }

    public function append(
        string $filename,
        string $data,
        int $mode = null,
        bool $ensureDirectory = false
    ): bool {
        $this->io->write(\sprintf('Appending to %s', $filename));
        $this->io->write($data);

        return $this->files->append($filename, $data, $mode, $ensureDirectory);
    }

    public function delete(string $filename): bool
    {
        $this->io->write(\sprintf('Deleting %s', $filename));

        return $this->files->delete($filename);
    }

    public function deleteDirectory(string $directory, bool $contentOnly = false): bool
    {
        $this->io->write(\sprintf('Deleting directory %s', $directory));

        return $this->files->deleteDirectory($directory, $contentOnly);
    }

    public function move(string $filename, string $destination): bool
    {
        $this->io->write(\sprintf('Moving %s to %s', $filename, $destination));

        return $this->files->move($filename, $destination);
    }

    public function copy(string $filename, string $destination): bool
    {
        $this->io->write(\sprintf('Copying %s to %s', $filename, $destination));

        return $this->files->copy($filename, $destination);
    }

    public function touch(string $filename, int $mode = null): bool
    {
        $this->io->write(\sprintf('Touching %s', $filename));

        return $this->files->touch($filename, $mode);
    }

    public function exists(string $filename): bool
    {
        return $this->files->exists($filename);
    }

    public function size(string $filename): int
    {
        return $this->files->size($filename);
    }

    public function extension(string $filename): string
    {
        return $this->files->extension($filename);
    }

    public function md5(string $filename): string
    {
        return $this->files->md5($filename);
    }

    public function time(string $filename): int
    {
        return $this->files->time($filename);
    }

    public function isDirectory(string $filename): bool
    {
        return $this->files->isDirectory($filename);
    }

    public function isFile(string $filename): bool
    {
        return $this->files->isFile($filename);
    }

    public function getPermissions(string $filename): int
    {
        return $this->files->getPermissions($filename);
    }

    public function setPermissions(string $filename, int $mode): bool
    {
        return $this->files->setPermissions($filename, $mode);
    }

    public function getFiles(string $location, string $pattern = null): array
    {
        return $this->files->getFiles($location, $pattern);
    }

    public function tempFilename(string $extension = '', string $location = null): string
    {
        return $this->files->tempFilename($extension, $location);
    }

    public function normalizePath(string $path, bool $asDirectory = false): string
    {
        return $this->files->normalizePath($path, $asDirectory);
    }

    public function relativePath(string $path, string $from): string
    {
        return $this->files->relativePath($path, $from);
    }
}
