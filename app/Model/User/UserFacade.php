<?php

declare(strict_types=1);

namespace Minecord\Model\User;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Security\Passwords;
use Ramsey\Uuid\UuidInterface;

class UserFacade extends UserRepository
{
	private EntityManagerInterface $entityManager;
	private UserFactory $userFactory;
	private Passwords $passwords;

	public function __construct(
		EntityManagerInterface $entityManager,
		UserFactory $userFactory,
		Passwords $passwords
	) {
		parent::__construct($entityManager);
		$this->entityManager = $entityManager;
		$this->userFactory = $userFactory;
		$this->passwords = $passwords;
	}

	public function create(UserData $userData): User
	{
		$user = $this->userFactory->create($userData);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return $user;
	}

	/**
	 * @throws Exception\UserNotFoundException
	 */
	public function edit(UuidInterface $id, UserData $userData, bool $flush = true): User
	{
		$user = $this->get($id);

		$user->edit($userData, $this->passwords);

		if ($flush) {
			$this->entityManager->flush();
		}

		return $user;
	}

	/**
	 * @throws Exception\UserNotFoundException
	 */
	public function delete(UuidInterface $id): bool
	{
		$user = $this->get($id);

		$this->entityManager->remove($user);
		$this->entityManager->flush();

		return $user->isRemoved();
	}
}
