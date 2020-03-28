<?php

declare(strict_types=1);

namespace Minecord\Model\Session;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;
use Minecord\Model\Session\Exception\SessionNotFoundException;

abstract class SessionRepository
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
			throw new SessionNotFoundException(sprintf('Session with id "%s" not found.', $id));
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
			throw new SessionNotFoundException(sprintf('Session with hash "%s" not found.', $hash));
		}

		return $session;
	}
}
