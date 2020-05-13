<?php

declare(strict_types=1);

namespace Minecord\Model\User\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Security\Passwords;
use Minecord\Model\User\User;
use Minecord\Model\User\UserFacade;
use Minecord\Model\User\Exception\UserNotFoundException;
use Minecord\Model\User\Exception\InvalidPasswordException;
use Minecord\Model\Session\SessionProvider;

class UserAuthenticator
{
	private UserFacade $userFacade;
	private Passwords $passwords;
	private SessionProvider $sessionProvider;
	private EntityManagerInterface $entityManager;

	public function __construct(
		UserFacade $userFacade,
		Passwords $passwords,
		SessionProvider $sessionProvider,
		EntityManagerInterface $entityManager
	) {
		$this->userFacade = $userFacade;
		$this->passwords = $passwords;
		$this->sessionProvider = $sessionProvider;
		$this->entityManager = $entityManager;
	}

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
