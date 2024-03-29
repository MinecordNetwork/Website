<?php

declare(strict_types=1);

namespace App\Model\Page;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Model\Page\Exception\PageNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class PageRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Page::class);
    }

    /**
     * @throws PageNotFoundException
     */
    public function get(UuidInterface $id): Page
    {
        /** @var Page $page */
        $page = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($page === null) {
            throw new PageNotFoundException(sprintf('Page with id "%s" not found.', $id));
        }

        return $page;
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
