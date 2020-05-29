<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use Ramsey\Uuid\UuidInterface;

final class SmsRecordFacade extends SmsRecordRepository
{
	private SmsRecordFactory $smsRecordFactory;
	private EntityManagerInterface $entityManager;

	public function __construct(SmsRecordFactory $smsRecordFactory, EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->smsRecordFactory = $smsRecordFactory;
		$this->entityManager = $entityManager;
	}

	public function create(SmsRecordData $data): SmsRecord
	{
		$smsRecord = $this->smsRecordFactory->create($data);

		$this->entityManager->persist($smsRecord);
		$this->entityManager->flush();

		return $smsRecord;
	}

	/**
	 * @throws SmsRecordNotFoundException
	 */
	public function delete(UuidInterface $id): void
	{
		$smsRecord = $this->get($id);

		$this->entityManager->remove($smsRecord);
		$this->entityManager->flush();
	}
}
