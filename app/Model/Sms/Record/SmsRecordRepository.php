<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class SmsRecordRepository
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
