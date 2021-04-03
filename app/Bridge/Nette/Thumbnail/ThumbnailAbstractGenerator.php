<?php

declare(strict_types=1);

namespace App\Bridge\Nette\Thumbnail;

abstract class ThumbnailAbstractGenerator
{
    protected ?string $src;
    protected string $desc;
    protected ?int $width;
    protected ?int $height;
    protected bool $crop;

    function __construct(
        private string $wwwDir, 
        private string $thumbPathMask, 
        private string $placeholder
    ) {}

    public function thumbnail(?string $src, ?int $width, ?int $height = null, $crop = false): string
    {
        return (($src == null || !is_file($this->wwwDir . '/' . $src)) ? null : '/') . $this->thumbnailRelative($src, $width, $height, $crop);
    }
    
    public function thumbnailRelative(?string $src, ?int $width, ?int $height = null, $crop = false): string
    {
        $this->src = $src === null ? null : $this->wwwDir . '/' . $src;
        $this->width = $width;
        $this->height = $height;
        $this->crop = $crop;

        if ($this->src === null || !is_file($this->src)) {
            return $this->createPlaceholderPath();
        }

        $thumbRelPath = $this->createThumbPath();
        $this->desc = $this->wwwDir . '/' . $thumbRelPath;

        if (!file_exists($this->desc) or (filemtime($this->desc) < filemtime($this->src))) {
            $this->createDir();
            $this->createThumb();
            clearstatcache();
        }

        return $thumbRelPath;
    }

    abstract protected function createThumb(): void;

    private function createDir(): void
    {
        $dir = dirname($this->desc);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    private function createThumbPath(): string
    {
        $pathInfo = pathinfo($this->src);
        $replace = [
            '{width}' => $this->width,
            '{height}' => $this->width,
            '{crop}' => (int) $this->crop,
            '{filename}' => $pathInfo['filename'],
            '{extension}' => $pathInfo['extension'], 
            '{dirname}' => basename(dirname($this->src))
        ];
        
        return str_replace(array_keys($replace), array_values($replace), $this->thumbPathMask);
    }

    private function createPlaceholderPath(): string
    {
        $width = $this->width === NULL ? $this->height : $this->width;
        $height = $this->height === NULL ? $this->width : $this->height;
        $replace = [
            '{width}' => $width, 
            '{height}' => $height
        ];
        
        return str_replace(array_keys($replace), array_values($replace), $this->placeholder);
    }
}