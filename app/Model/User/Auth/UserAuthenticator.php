<?php

declare(strict_types=1);

namespace App\Model\User\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Security\Passwords;
use App\Model\User\User;
use App\Model\User\UserFacade;
use App\Model\User\Exception\UserNotFoundException;
use App\Model\User\Exception\InvalidPasswordException;
use App\Model\Session\SessionProvider;

class UserAuthenticator
{
    public function __construct(
        private UserFacade $userFacade,
        private Passwords $passwords,
        private SessionProvider $sessionProvider,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @throws InvalidPasswordException
     * @throws UserNotFoundException
     */
    public function authenticate(string $email, string $password): User
    {
        $user = $this->userFacade->getByEmail($email);

        if (!$user->checkPassword($password, $this->passwords)) {
            throw new InvalidPasswordException('Password is invalid');
        }

        $this->sessionProvider->provide()->authenticateUser($user);
        $this->entityManager->flush();

        return $user;
    }

    public function logOut(): void
    {
        $this->sessionProvider->provide()->logOutUser();
        $this->entityManager->flush();
    }
}
