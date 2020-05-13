<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use Doctrine\ORM\EntityManagerInterface;
use Minecord\Model\Page\Exception\PageNotFoundException;
use Minecord\Model\Image\Image;
use Ramsey\Uuid\UuidInterface;

final class PageFacade extends PageRepository
{
	private PageFactory $pageFactory;
	private EntityManagerInterface $entityManager;

	public function __construct(PageFactory $PageFactory, EntityManagerInterface $entityManager)
	{
		parent::__construct($entityManager);
		$this->pageFactory = $PageFactory;
		$this->entityManager = $entityManager;
	}

	public function create(PageData $data): Page
	{
		$page = $this->pageFactory->create($data);

		$this->entityManager->persist($page);
		$this->entityManager->flush();

		return $page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function edit(UuidInterface $id, PageData $data): Page
	{
		$page = $this->get($id);

		$page->edit($data);
		$this->entityManager->flush();

		return $page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function changeThumbnail(UuidInterface $id, Image $image): Page
	{
		$page = $this->get($id);

		$page->changeThumbnail($image);
		$this->entityManager->flush();

		return $page;
	}

	/**
	 * @throws PageNotFoundException
	 */
	public function delete(UuidInterface $id): void
	{
		$page = $this->get($id);

		$this->entityManager->remove($page);
		$this->entityManager->flush();
	}
}
