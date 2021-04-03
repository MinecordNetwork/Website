<?php

declare(strict_types=1);

namespace App\Model\Image;

use Nette\Http\FileUpload;

class ImageDataFactory
{
    public function createFromFormData(array $formData, int $type = Image::TYPE_ARTICLE): ImageData
    {
        $data = new ImageData();
        $data->type = $type;
        $data->alternativeText = $formData['alternativeText'] ?? null;
        $data->caption = $formData['caption'] ?? null;

        if ($formData['image'] instanceof FileUpload) {
            /** @var FileUpload $netteImage */
            $netteImage = $formData['image'];
            $data->originalName = $netteImage->getName();
        }

        return $data;
    }

    public function createFromFileUpload(FileUpload $netteImage, int $type = Image::TYPE_ARTICLE): ImageData
    {
        $data = new ImageData();
        $data->type = $type;
        $data->originalName = $netteImage->getName();

        return $data;
    }
}
