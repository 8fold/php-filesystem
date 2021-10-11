<?php

declare(strict_types=1);

namespace Eightfold\FileSystem;

use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use Eightfold\FileSystem\FileSystemItem;

// Flysystem 2.0
// use League\Flysystem\Local\LocalFilesystemAdapter;
// use League\Flysystem\Filesystem;
// use League\Flysystem\StorageAttributes;

trait FlySystem
{
    /**
     * @var LeagueFilesystem|\League\Flysystem\Filesystem
     */
    private $fileSystem;

    /**
     * If the path is to a file, it returns the content of the file; otherwise,
     * returns list of files and folders within a folder.
     *
     * @param  bool|boolean $includeFiles   [description]
     * @param  bool|boolean $includeFolders [description]
     * @return string|bool|boolean|array<Item>        [description]
     */
    // rename to theContent
    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true
    ) {
        if ($this->isFile()) {
            return $this->fileSystem()->read(
                $this->thePath()
            );

        }

        $folders = [];
        $files   = [];

        foreach ($this->fileSystem()->listContents($this->thePath()) as $item) {
            $path = '/' . $item['path'];

            if ($item['type'] === 'dir') {
                $folders[] = Item::create($path);

            } elseif ($item['type'] === 'file') {
                $files[] = Item::create($path);

            }
        }

        return array_merge($folders, $files);
    }

    public function save(string $content = ''): Item
    {
        $this->fileSystem()->write($this->thePath(), $content);
        return $this;
    }

    public function delete(): Item
    {
        if ($this->doesExist() and $this->isFile()) {
            $this->fileSystem()->delete($this->thePath());

        } elseif ($this->doesExist() and $this->isFolder()) {
            $this->fileSystem()->deleteDir($this->thePath());

        }
        return $this;
    }

    public function doesExist(): bool
    {
        return $this->fileSystem()->has($this->thePath());
        // return file_exists($this->thePath());
    }

    /**
     * @return string|false [description]
     */
    public function theMimeType()
    {
        return $this->fileSystem()->getMimetype($this->thePath());
    }

    public function didSave(string $content = ''): bool
    {
        return $this->save($content)->doesExist();
    }

    public function didDelete(): bool
    {
        return ! $this->delete()->doesExist();
    }

    /**
     * @return LeagueFilesystem|\League\Flysystem\Filesystem [description]
     */
    private function fileSystem()
    {
        if ($this->fileSystem === null) {
            if ($this->shouldUseFlysystem2()) {
                // Always receive fully qualified paths from root
                /** @phpstan-ignore-next-line */
                $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter('/');

                /** @phpstan-ignore-next-line */
                $this->fileSystem = new \League\Flysystem\Filesystem($adapter);

            } else {
                // Always receive fully qualified paths from root
                $adapter = new Local('/');

                $this->fileSystem = new LeagueFilesystem($adapter);

            }
        }
        return $this->fileSystem;
    }

    private function shouldUseFlysystem2(): bool
    {
        return class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class);
    }
}
