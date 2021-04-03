<?php

declare(strict_types=1);

namespace App\Model\Image;

class ImageConfig
{
    /** Path for saving new images */
    private string $savePath;

    /** Path for getting images through http */
    private string $publicPath;

    /** Path for caching images, for instance "../thumbs" */
    private string $cachePath;

    /** Convert all PNG/JPEG to WebP format? */
    private bool $webpOptimization;

    public function __construct(
        string $savePath = __DIR__ . '/../../../../../../storage/images/uploads/$type/$year/$month/$id/',
        string $publicPath = '/img/uploads/$type/$year/$month/$id/',
        string $cachePath = 'images/',
        bool $webpOptimization = true
    ) {
        $this->savePath = $savePath;
        $this->cachePath = $cachePath;
        $this->publicPath = $publicPath;
        $this->webpOptimization = $webpOptimization;
    }

    public function getSavePath(Image $image = null): string
    {
        if ($image !== null) {
            $this->savePath = str_replace([
                '$type',
                '$year',
                '$month',
                '$id'
            ], [
                Image::TYPES[$image->getType()],
                $image->getCreatedAt()->format('Y'),
                $image->getCreatedAt()->format('m'),
                (string) $image->getId()
            ], $this->savePath);
        }

        @mkdir($this->savePath, 0755, true);

        return $this->savePath;
    }

    public function getPublicPath(Image $image = null): string
    {
        if ($image !== null) {
            $this->publicPath = str_replace([
                '$type',
                '$year',
                '$month',
                '$id'
            ], [
                Image::TYPES[$image->getType()],
                $image->getCreatedAt()->format('Y'),
                $image->getCreatedAt()->format('m'),
                (string) $image->getId()
            ], $this->publicPath);
        }

        return $this->publicPath;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }

    public function isWebpOptimization(): bool
    {
        return $this->webpOptimization;
    }
}
