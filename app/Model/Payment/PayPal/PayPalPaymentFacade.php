<?php

declare(strict_types=1);

namespace App\Model\Payment\PayPal;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\Payment\PayPal\Event\PayPalPaymentAcceptedEvent;
use App\Model\Payment\PayPal\Event\PayPalPaymentCreatedEvent;
use App\Model\Payment\PayPal\Exception\InvalidPayPalPaymentException;
use App\Model\Payment\PayPal\Exception\PayPalPaymentNotFoundException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PayPalPaymentFacade extends PayPalPaymentRepository
{
    public function __construct(
        private PayPalPaymentFactory $payPalPaymentFactory,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager);
    }

    public function create(PayPalPaymentData $data): PayPalPayment
    {
        $payPalPayment = $this->payPalPaymentFactory->create($data);

        $this->entityManager->persist($payPalPayment);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new PayPalPaymentCreatedEvent($payPalPayment));

        return $payPalPayment;
    }

    /**
     * @throws PayPalPaymentNotFoundException
     * @throws InvalidPayPalPaymentException
     */
    public function onAccept(UuidInterface $payPalPaymentId, string $email, float $price, string $currency): void
    {
        $payPalPayment = $this->get($payPalPaymentId);

        if ($payPalPayment->isAccepted()) {
            throw new InvalidPayPalPaymentException('PayPalPayment ' . $payPalPayment->getId() . ' was already accepted.');
        }

        if (round($payPalPayment->getPrice(), 2) !== round($price, 2)) {
            throw new InvalidPayPalPaymentException('PayPalPayment ' . $payPalPayment->getId() . ' has different price (' . $payPalPayment->getPrice() . ' != ' . $price . ').');
        }

        if ($payPalPayment->getCurrency()->getCode() !== $currency) {
            throw new InvalidPayPalPaymentException('PayPalPayment ' . $payPalPayment->getId() . ' has different currency (' . $payPalPayment->getCurrency()->getCode() . ' != ' . $currency . ').');
        }

        $payPalPayment->onAccept($email);

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new PayPalPaymentAcceptedEvent($payPalPayment));
    }
}
