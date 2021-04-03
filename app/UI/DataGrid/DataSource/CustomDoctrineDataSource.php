<?php

declare(strict_types=1);

namespace App\UI\DataGrid\DataSource;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Ublaboo\DataGrid\DataSource\DoctrineDataSource;
use Ublaboo\DataGrid\Filter\FilterSelect;

/**
 * @method void onDataLoaded(array $result)
 */
class CustomDoctrineDataSource extends DoctrineDataSource
{
    public function __construct(QueryBuilder $dataSource, string $primaryKey = 'id')
    {
        parent::__construct($dataSource, $primaryKey);
    }

    private function checkAliases($column)
    {
        if (Strings::contains($column, '.')) {
            return $column;
        }
        if (!isset($this->rootAlias)) {
            $ras = $this->dataSource->getRootAliases();
            $this->rootAlias = current($ras);
        }
        return $this->rootAlias . '.' . $column;
    }

    private function usePaginator()
    {
        return $this->dataSource->getDQLPart('join') || $this->dataSource->getDQLPart('groupBy');
    }


    public function getCount(): int
    {
        if ($this->usePaginator()) {
            return (new Paginator($this->getQuery(), false))->count();
        }
        $dataSource = clone $this->dataSource;
        $dataSource->select(sprintf('COUNT(%s)', $this->checkAliases($this->primaryKey)));
        $dataSource->resetDQLPart('orderBy');
        return (int) $dataSource->getQuery()->getSingleScalarResult();
    }

    public function getData(): array
    {
        if ($this->usePaginator()) {
            $iterator = (new Paginator($this->getQuery(), false))->getIterator();
            $data = iterator_to_array($iterator);
        } else {
            $data = $this->getQuery()->getResult();
        }
        $this->onDataLoaded($data);
        return $data;
    }

    protected function applyFilterSelect(FilterSelect $filter): void
    {
        $p = $this->getPlaceholder();

        foreach ($filter->getCondition() as $column => $value) {
            $c = $this->checkAliases($column);

            if (Strings::endsWith($column, '.id')) {
                $value = Uuid::fromString($value)->getBytes();
            }

            $this->dataSource->andWhere("$c = :$p")
                ->setParameter($p, $value);
        }
    }
}
