<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Minecord\Model\Admin\Exception\AdminNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class AdminRepository
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return EntityRepository|ObjectRepository
	 */
	private function getRepository()
	{
		return $this->entityManager->getRepository(Admin::class);
	}

	/**
	 * @throws AdminNotFoundException
	 */
	public function get(UuidInterface $id): Admin
	{
		/** @var Admin $admin */
		$admin = $this->getRepository()->find($id);

		if ($admin === null) {
			throw AdminNotFoundException::byId($id);
		}

		return $admin;
	}

	/**
	 * @throws AdminNotFoundException
	 */
	public function getByEmail(string $email): Admin
	{
		/** @var Admin $admin */
		$admin = $this->getRepository()->findOneBy([
			'email' => $email
		]);

		if ($admin === null) {
			throw AdminNotFoundException::byEmail($email);
		}

		return $admin;
	}

	/**
	 * @return Admin[]
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
