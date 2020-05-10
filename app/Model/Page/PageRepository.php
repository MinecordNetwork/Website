<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\Page\Exception\PageNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class PageRepository
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityRepository|ObjectRepository
	 */
	private function getRepository()
	{
		return $this->entityManager->getRepository(Page::class);
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function get(UuidInterface $id): Page
	{
		/** @var Page $Page */
		$Page = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($Page === null) {
			throw new PageNotFoundException(sprintf('Page with id "%s" not found.', $id));
		}

		return $Page;
	}

	/**
	 * @return Page[]
	 */
	public function getAllByAuthor(UuidInterface $authorId): array
	{
		return $this->getRepository()->findBy([
			'author' => $authorId
		]);
	}

	/**
	 * @return Page[]
	 */
	public function getAll(): array
	{
		return $this->getQueryBuilderForAll()->getQuery()->execute();
	}

	private function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e');
	}

	public function getQueryBuilderForDataGrid(): QueryBuilder
	{
		return $this->getQueryBuilderForAll();
	}
}
