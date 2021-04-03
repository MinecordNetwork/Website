<?php

declare(strict_types=1);

namespace App\Model\Image;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use App\Model\Image\Exception\ImageNotFoundException;

class ImageFacade extends ImageRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ImageFactory $imageFactory,
        private ImageConfig $imageConfig
    ) {
        parent::__construct($entityManager);
    }

    public function create(ImageData $imageData, callable $saveFunction): Image
    {
        $image = $this->imageFactory->create($imageData);

        $fileName = (string) Strings::webalize(pathinfo($imageData->originalName, PATHINFO_FILENAME)) . '.' . pathinfo($imageData->originalName, PATHINFO_EXTENSION);
        $imageConfig = clone $this->imageConfig;
        $path = $imageConfig->getSavePath($image) . $fileName;
        $publicPath = $imageConfig->getPublicPath($image) . $fileName;
        $saveFunction($path);
        $image->onFileSave($path, $publicPath);

        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $image;
    }

    /**
     * @throws ImageNotFoundException
     */
    public function edit(UuidInterface $id, ImageData $imageData): Image
    {
        $image = $this->get($id);

        $image->edit($imageData);
        $this->entityManager->flush();

        return $image;
    }

    /**
     * @throws Exception\ImageNotFoundException
     */
    public function remove(UuidInterface $id): void
    {
        $image = $this->get($id);

        @unlink($image->getPath());
        @rmdir(dirname($image->getPath()));

        $this->entityManager->remove($image);

        $this->entityManager->flush();
    }
}
