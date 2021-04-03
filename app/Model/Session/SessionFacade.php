<?php

declare(strict_types=1);

namespace App\Model\Session;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class SessionFacade extends SessionRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SessionFactory $sessionFactory
    ) {
        parent::__construct($entityManager);
    }

    public function create(SessionData $sessionData): Session
    {
        $session = $this->sessionFactory->create($sessionData);

        $this->entityManager->persist($session);
        $this->entityManager->flush();

        return $session;
    }

    /**
     * @throws Exception\SessionNotFoundException
     */
    public function edit(UuidInterface $id, SessionData $sessionData): Session
    {
        $session = $this->get($id);

        $session->edit($sessionData);

        $this->entityManager->flush();

        return $session;
    }
}
