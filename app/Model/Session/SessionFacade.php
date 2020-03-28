<?php

declare(strict_types=1);

namespace Minecord\Model\Session;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class SessionFacade extends SessionRepository
{
	private EntityManagerInterface $entityManager;
	private SessionFactory $sessionFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		SessionFactory $sessionFactory
	) {
		parent::__construct($entityManager);
		$this->entityManager = $entityManager;
		$this->sessionFactory = $sessionFactory;
	}

	public function create(SessionData $sessionData): Session
	{
		$session = $this->sessionFactory->create($sessionData);

		$this->entityManager->persist($session);
		$this->entityManager->flush();

		return $session;
	}

	/**
	 * @throws Exception\SessionNotFoundException
	 */
	public function edit(UuidInterface $id, SessionData $sessionData): Session
	{
		$session = $this->get($id);

		$session->edit($sessionData);

		$this->entityManager->flush();

		return $session;
	}
}
