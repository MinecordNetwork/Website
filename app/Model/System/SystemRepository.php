<?php

declare(strict_types=1);

namespace App\Model\System;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class SystemRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(System::class);
    }

    public function get(): System
    {
        return $this->getRepository()->findOneBy([]);
    }
}
