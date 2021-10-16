<?php

declare(strict_types=1);

namespace Eightfold\FileSystem;

class Item
{
    use FlySystem;

    private string $path;

    public static function create(string $path): Item
    {
        return new Item($path);
    }

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function up(int $levels = 1): Item
    {
        $pp = $this->pathParts();
        $pp = array_slice($pp, 0, -1 * $levels);

        $this->path = $this->pathFromParts($pp);

        return Item::create(
            $this->pathFromParts($pp)
        );
    }

    public function append(string ...$parts): Item
    {
        $pp = array_merge($this->pathParts(), $parts);

        return Item::create(
            $this->pathFromParts($pp)
        );
    }

    public function thePath(): string
    {
        if (substr($this->path, 0, 1) === '/') {
            return $this->path;
        }
        return '/' . $this->path;
    }

    public function isFile(): bool
    {
        return is_file($this->thePath());
    }

    public function isFolder(): bool
    {
        return is_dir($this->thePath());
    }

    /**
     * @return array<string> [description]
     */
    public function thePathParts(): array
    {
        return explode('/', $this->thePath());
    }

    /**
     * @param  array<string>  $parts [description]
     */
    private function pathFromParts(array $parts = []): string
    {
        return implode('/', $parts);
    }
}
