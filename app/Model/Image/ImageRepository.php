<?php

declare(strict_types=1);

namespace App\Model\Image;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use App\Model\Image\Exception\ImageNotFoundException;

abstract class ImageRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Image::class);
    }

    /**
     * @throws ImageNotFoundException
     */
    public function get(UuidInterface $id): Image
    {
        /** @var Image $image */
        $image = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($image === null) {
            throw new ImageNotFoundException('Image with id ' . $id . ' not found.');
        }

        return $image;
    }

    /**
     * @throws ImageNotFoundException
     */
    public function getByPath(string $path): Image
    {
        /** @var Image $image */
        $image = $this->getRepository()->findOneBy([
            'path' => $path
        ]);

        if ($image === null) {
            throw new ImageNotFoundException('Image with path ' . $path . ' not found.');
        }

        return $image;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC');
    }
}
