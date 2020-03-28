<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Security\Passwords;
use Ramsey\Uuid\UuidInterface;

class AdminFacade extends AdminRepository
{
	private EntityManagerInterface $entityManager;
	private AdminFactory $adminFactory;
	private Passwords $passwords;

	public function __construct(
		EntityManagerInterface $entityManager,
		AdminFactory $adminFactory,
		Passwords $passwords
	) {
		parent::__construct($entityManager);
		$this->entityManager = $entityManager;
		$this->adminFactory = $adminFactory;
		$this->passwords = $passwords;
	}

	public function create(AdminData $adminData): Admin
	{
		$admin = $this->adminFactory->create($adminData);

		$this->entityManager->persist($admin);
		$this->entityManager->flush();

		return $admin;
	}

	/**
	 * @throws Exception\AdminNotFoundException
	 */
	public function edit(UuidInterface $id, AdminData $adminData, bool $flush = true): Admin
	{
		$admin = $this->get($id);

		$admin->edit($adminData, $this->passwords);

		if ($flush) {
			$this->entityManager->flush();
		}

		return $admin;
	}

	/**
	 * @throws Exception\AdminNotFoundException
	 */
	public function delete(UuidInterface $id): bool
	{
		$admin = $this->get($id);

		$this->entityManager->remove($admin);
		$this->entityManager->flush();

		return $admin->isRemoved();
	}
}
