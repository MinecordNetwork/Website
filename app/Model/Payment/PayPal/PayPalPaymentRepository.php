<?php

declare(strict_types=1);

namespace App\Model\Payment\PayPal;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use App\Model\Payment\PayPal\Exception\PayPalPaymentNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class PayPalPaymentRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
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
    
    /**
     * @return PayPalPayment[]
     */
    public function getLatestSuccessfulPayments(int $limit = 5): array
    {
        return $this->getQueryBuilderForAll()
            ->where('e.acceptedAt IS NOT NULL')
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e');
    }
}
