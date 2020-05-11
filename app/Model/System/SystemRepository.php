<?php

declare(strict_types=1);

namespace Minecord\Model\System;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Minecord\Model\System\Exception\SystemNotFoundException;

abstract class SystemRepository
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
		return $this->entityManager->getRepository(System::class);
	}

	/**
	 * @throws SystemNotFoundException
	 */
	public function get(): System
	{
		/** @var System $system */
		$system = $this->getRepository()->findOneBy([]);

		if ($system === null) {
			throw new SystemNotFoundException(sprintf('System not found.'));
		}

		return $system;
	}
}
