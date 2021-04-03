<?php

declare(strict_types=1);

namespace App\Model\Session;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use App\Model\Session\Exception\SessionNotFoundException;

abstract class SessionRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Session::class);
    }

    /**
     * @throws SessionNotFoundException
     */
    public function get(UuidInterface $id): Session
    {
        /** @var Session $session */
        $session = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($session === null) {
            throw new SessionNotFoundException();
        }

        return $session;
    }

    /**
     * @throws SessionNotFoundException
     */
    public function getByHash(string $hash): Session
    {
        /** @var Session $session */
        $session = $this->getRepository()->findOneBy([
            'hash' => $hash
        ]);

        if ($session === null) {
            throw new SessionNotFoundException();
        }

        return $session;
    }
}
