<?php

declare(strict_types=1);

namespace App\Model\User;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use App\Model\User\Exception\UserNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}
    
    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UuidInterface $id): User
    {
        /** @var User $user */
        $user = $this->getRepository()->find($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User
    {
        /** @var User $user */
        $user = $this->getRepository()->findOneBy([
            'email' => $email
        ]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e')
            ->where('e.isRemoved = :removed')->setParameter('removed', false);
    }

    public function getQueryBuilderForDataGrid(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e');
    }
}
