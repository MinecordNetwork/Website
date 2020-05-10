<?php

declare(strict_types=1);

namespace Minecord\Model\Article;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\Article\Exception\ArticleNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class ArticleRepository
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
		return $this->entityManager->getRepository(Article::class);
	}

	/**
	 * @throws ArticleNotFoundException
	 */
	public function get(UuidInterface $id): Article
	{
		/** @var Article $article */
		$article = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($article === null) {
			throw new ArticleNotFoundException(sprintf('Article with id "%s" not found.', $id));
		}

		return $article;
	}

	/**
	 * @return Article[]
	 */
	public function getAllByAuthor(UuidInterface $authorId): array
	{
		return $this->getRepository()->findBy([
			'author' => $authorId
		]);
	}

	/**
	 * @return Article[]
	 */
	public function getAll(): array
	{
		return $this->getQueryBuilderForAll()->getQuery()->execute();
	}

	/**
	 * @return Article[]
	 */
	public function getAllOrderedByCreatedAt(int $limit = 20, int $offset = 0): array
	{
		return $this->getQueryBuilderForAllOrderedByCreatedAt($limit, $offset)->getQuery()->execute();
	}

	private function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e');
	}

	public function getQueryBuilderForDataGrid(): QueryBuilder
	{
		return $this->getQueryBuilderForAll();
	}

	public function getQueryBuilderForAllOrderedByCreatedAt(int $limit = 20, int $offset = 0): QueryBuilder
	{
		return $this->getQueryBuilderForAll()
			->orderBy('e.createdAt', 'DESC')
			->setMaxResults($limit)
			->setFirstResult($offset);
	}
}
