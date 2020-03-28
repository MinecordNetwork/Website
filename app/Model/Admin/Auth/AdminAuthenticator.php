<?php

declare(strict_types=1);

namespace Minecord\Model\Admin\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Security\Passwords;
use Minecord\Model\Admin\Admin;
use Minecord\Model\Admin\AdminFacade;
use Minecord\Model\Admin\Exception\AdminNotFoundException;
use Minecord\Model\Admin\Exception\InvalidPasswordException;
use Minecord\Model\Session\SessionProvider;

class AdminAuthenticator
{
	private AdminFacade $adminFacade;
	private Passwords $passwords;
	private SessionProvider $sessionProvider;
	private EntityManagerInterface $entityManager;

	public function __construct(
		AdminFacade $adminFacade,
		Passwords $passwords,
		SessionProvider $sessionProvider,
		EntityManagerInterface $entityManager
	) {
		$this->adminFacade = $adminFacade;
		$this->passwords = $passwords;
		$this->sessionProvider = $sessionProvider;
		$this->entityManager = $entityManager;
	}

	/**
	 * @throws InvalidPasswordException
	 * @throws AdminNotFoundException
	 */
	public function authenticate(string $email, string $password): Admin
	{
		$admin = $this->adminFacade->getByEmail($email);

		if (!$admin->checkPassword($password, $this->passwords)) {
			throw new InvalidPasswordException('Password is invalid');
		}

		$this->sessionProvider->provide()->authenticateAdmin($admin);
		$this->entityManager->flush();

		return $admin;
	}

	public function logOut(): void
	{
		$this->sessionProvider->provide()->logOutAdmin();
		$this->entityManager->flush();
	}
}
