<?php

declare(strict_types=1);

namespace App\Model\Sms\Record;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class SmsRecordRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(SmsRecord::class);
    }

    /**
     * @throws SmsRecordNotFoundException
     */
    public function get(UuidInterface $id): SmsRecord
    {
        /** @var SmsRecord $smsRecord */
        $smsRecord = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($smsRecord === null) {
            throw new SmsRecordNotFoundException(sprintf('SmsRecord with id "%s" not found.', $id));
        }

        return $smsRecord;
    }

    /**
     * @throws SmsRecordNotFoundException
     */
    public function getByExternalId(int $externalId): SmsRecord
    {
        /** @var SmsRecord $smsRecord */
        $smsRecord = $this->getRepository()->findOneBy([
            'externalId' => $externalId
        ]);

        if ($smsRecord === null) {
            throw new SmsRecordNotFoundException(sprintf('SmsRecord with externalId "%s" not found.', $externalId));
        }

        return $smsRecord;
    }

    public function getRealValue(int $year, int $month): int
    {
        return (int) $this->getQueryBuilderForAll()
            ->select('SUM(e.stockCount)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return SmsRecord[]
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
