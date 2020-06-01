<?php

declare(strict_types=1);

namespace Minecord\Model\Payment\PayPal;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\Payment\PayPal\Exception\PayPalPaymentNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class PayPalPaymentRepository
{
	private EntityManagerInterface $entityManager;

	public function __construct(
		EntityManagerInterface $entityManager
	) {
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityRepository|ObjectRepository
	 */
	private function getRepository()
	{
		return $this->entityManager->getRepository(PayPalPayment::class);
	}

	/**
	 * @throws PayPalPaymentNotFoundException
	 */
	public function get(UuidInterface $id): PayPalPayment
	{
		/** @var PayPalPayment $payment */
		$payment = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($payment === null) {
			throw new PayPalPaymentNotFoundException(sprintf('PayPalPayment with id "%s" not found.', $id));
		}

		return $payment;
	}

	public function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e');
	}
}
