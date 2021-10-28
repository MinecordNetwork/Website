<?php

declare(strict_types=1);

namespace App\Model\System;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Model\System\Exception\SystemNotFoundException;

abstract class SystemRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(System::class);
    }

    /**
     * @throws SystemNotFoundException
     */
    public function get(): System
    {
        /** @var System $system */
        $system = $this->getRepository()->findOneBy([]);

        if ($system === null) {
            throw new SystemNotFoundException();
        }

        return $system;
    }
}
