<?php

declare(strict_types=1);

namespace App\Model\Sms\Record;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\Sms\Record\Event\SmsRecordConfirmedEvent;
use App\Model\Sms\Record\Event\SmsRecordCreatedEvent;
use App\Model\Sms\Record\Event\SmsRecordPreCreatedEvent;
use App\Model\Sms\Record\Exception\SmsRecordNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\UuidInterface;

final class SmsRecordFacade extends SmsRecordRepository
{
    public function __construct(
        private SmsRecordFactory $smsRecordFactory,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager);
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
