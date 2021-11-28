<?php

declare(strict_types=1);

namespace App\Model\Article;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Model\Article\Exception\ArticleNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class ArticleRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
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

    public function getCount(): int
    {
        return (int) $this->getQueryBuilderForAll()
            ->select('COUNT(DISTINCT e.id)')
            ->getQuery()
            ->getSingleScalarResult();
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
    public function getAllByNotifiedDiscord(bool $notifiedDiscord): array
    {
        return $this->getRepository()->findBy([
            'notifiedDiscord' => $notifiedDiscord
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
