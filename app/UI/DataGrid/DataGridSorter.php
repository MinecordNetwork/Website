<?php

declare(strict_types=1);

namespace App\UI\DataGrid;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class DataGridSorter
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sort($facade, ?string $itemId, ?string $prevId, ?string $nextId, ?string $parentId = null): void
    {
        $item = $facade->get(Uuid::fromString($itemId));

        if ($parentId !== null) {
            if (!$parentId) {
                $item->changeParent(null);
            } else {
                $parent = $facade->get(Uuid::fromString($parentId));
                $item->changeParent($parent);
            }
        }

        if (!$prevId) {
            $previousItem = null;
        } else {
            $previousItem = $facade->get(Uuid::fromString($prevId));
        }

        if (!$nextId) {
            $nextItem = null;
        } else {
            $nextItem = $facade->get(Uuid::fromString($nextId));
        }

        $itemsToMoveUp = $facade->getQueryBuilderForAll()
            ->andWhere('e.sortOrder <= :sortOrder')
            ->setParameter('sortOrder', $previousItem ? $previousItem->getSortOrder() : 0)
            ->andWhere('e.sortOrder > :sortOrder2')
            ->setParameter('sortOrder2', $item->getSortOrder())
            ->getQuery()
            ->getResult();

        foreach ($itemsToMoveUp as $t) {
            $t->setSortOrder($t->getSortOrder() - 1);
            $this->entityManager->persist($t);
        }

        $itemsToMoveDown = $facade->getQueryBuilderForAll()
            ->andWhere('e.sortOrder >= :sortOrder')
            ->setParameter('sortOrder', $nextItem ? $nextItem->getSortOrder() : 0)
            ->andWhere('e.sortOrder < :sortOrder2')
            ->setParameter('sortOrder2', $item->getSortOrder())
            ->getQuery()
            ->getResult();

        if ($nextItem) {
            foreach ($itemsToMoveDown as $t) {
                $t->setSortOrder($t->getSortOrder() + 1);
                $this->entityManager->persist($t);
            }
        }

        if ($previousItem) {
            $item->setSortOrder($previousItem->getSortOrder() + 1);
        } else if ($nextItem) {
            $item->setSortOrder($nextItem->getSortOrder() - 1);
        } else {
            $item->setSortOrder(1);
        }

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
}
