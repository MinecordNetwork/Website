<?php

declare(strict_types=1);

namespace App\Model\System;

use Doctrine\ORM\EntityManagerInterface;

final class SystemFacade extends SystemRepository
{
    public function __construct(
        private SystemFactory $systemFactory,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct($entityManager);
    }

    public function create(SystemData $data): System
    {
        $system = $this->systemFactory->create($data);

        $this->entityManager->persist($system);
        $this->entityManager->flush();

        return $system;
    }

    public function edit(SystemData $data): System
    {
        $system = $this->get();

        $system->edit($data);
        $this->entityManager->flush();

        return $system;
    }
}
