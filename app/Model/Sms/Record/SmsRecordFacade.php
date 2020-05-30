<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\Sms\Record\Event\SmsRecordConfirmedEvent;
use Minecord\Model\Sms\Record\Event\SmsRecordCreatedEvent;
use Minecord\Model\Sms\Record\Event\SmsRecordPreCreatedEvent;
use Minecord\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\UuidInterface;

final class SmsRecordFacade extends SmsRecordRepository
{
	private SmsRecordFactory $smsRecordFactory;
	private EntityManagerInterface $entityManager;
	private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		SmsRecordFactory $smsRecordFactory,
		EntityManagerInterface $entityManager, 
		EventDispatcherInterface $eventDispatcher
	) {
		parent::__construct($entityManager);
		$this->smsRecordFactory = $smsRecordFactory;
		$this->entityManager = $entityManager;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function create(SmsRecordData $data): SmsRecord
	{
		$smsRecord = $this->smsRecordFactory->create($data);

		$this->eventDispatcher->dispatch(new SmsRecordPreCreatedEvent($smsRecord));

		$this->entityManager->persist($smsRecord);
		$this->entityManager->flush();
		
		$this->eventDispatcher->dispatch(new SmsRecordCreatedEvent($smsRecord));

		return $smsRecord;
	}

	public function onConfirm(UuidInterface $id): SmsRecord
	{
		$smsRecord = $this->get($id);
		
		$smsRecord->onConfirm();

		$this->entityManager->flush();
		
		$this->eventDispatcher->dispatch(new SmsRecordConfirmedEvent($smsRecord));

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
