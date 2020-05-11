<?php

declare(strict_types=1);

namespace Minecord\Model\System;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\System\Exception\SystemNotFoundException;

final class SystemFacade extends SystemRepository
{
	private SystemFactory $systemFactory;
	private EntityManagerInterface $entityManager;

	public function __construct(SystemFactory $systemFactory, EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->systemFactory = $systemFactory;
		$this->entityManager = $entityManager;
	}

	public function create(SystemData $data): System
	{
		$system = $this->systemFactory->create($data);

		$this->entityManager->persist($system);
		$this->entityManager->flush();

		return $system;
	}

	/**
	 * @throws SystemNotFoundException
	 */
	public function edit(SystemData $data): System
	{
		$system = $this->get();

		$system->edit($data);
		$this->entityManager->flush();

		return $system;
	}
}
