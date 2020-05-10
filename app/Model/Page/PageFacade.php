<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\Page\Exception\PageNotFoundException;
use Minecord\Model\Image\Image;
use Ramsey\Uuid\UuidInterface;

final class PageFacade extends PageRepository
{
	private PageFactory $PageFactory;
	private EntityManagerInterface $entityManager;

	public function __construct(PageFactory $PageFactory, EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->PageFactory = $PageFactory;
		$this->entityManager = $entityManager;
	}

	public function create(PageData $data): Page
	{
		$Page = $this->PageFactory->create($data);

		$this->entityManager->persist($Page);
		$this->entityManager->flush();

		return $Page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function edit(UuidInterface $id, PageData $data): Page
	{
		$Page = $this->get($id);

		$Page->edit($data);
		$this->entityManager->flush();

		return $Page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function changeThumbnail(UuidInterface $id, Image $image): Page
	{
		$Page = $this->get($id);

		$Page->changeThumbnail($image);
		$this->entityManager->flush();

		return $Page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function delete(UuidInterface $id): void
	{
		$Page = $this->get($id);

		$this->entityManager->remove($Page);
		$this->entityManager->flush();
	}
}
