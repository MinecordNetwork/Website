<?php

declare(strict_types=1);

namespace Minecord\Model\Image;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Minecord\Model\Image\Exception\ImageNotFoundException;

abstract class ImageRepository
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityRepository|ObjectRepository
	 */
	protected function getRepository()
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
