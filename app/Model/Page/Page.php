<?php

declare(strict_types=1);

namespace Minecord\Model\Page;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Minecord\Model\Admin\Admin;
use Minecord\Model\Image\Image;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\LanguageStaticHolder;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	private UuidInterface $id;

	/** @ORM\Column(type="string", unique=true) */
	private string $routeCzech;

	/** @ORM\Column(type="string", unique=true) */
	private string $routeEnglish;

	/** @ORM\Column(type="string", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $titleEnglish;

	/** @ORM\Column(type="string", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $titleCzech;

	/** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $contentEnglish;

	/** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $contentCzech;

	/** @ORM\ManyToOne(targetEntity="\Minecord\Model\Admin\Admin") */
	private Admin $author;

	/** @ORM\ManyToOne(targetEntity="\Minecord\Model\Image\Image") */
	private ?Image $thumbnail = null;

	/** @ORM\Column(type="integer") */
	private int $views = 0;

	/** @ORM\Column(type="datetime") */
	private DateTime $createdAt;

	/** @ORM\Column(type="datetime") */
	private DateTime $editedAt;

	public function __construct(UuidInterface $id, PageData $data)
	{
		$this->id = $id;
		$this->author = $data->author;
		$this->createdAt = new DateTime();
		$this->edit($data);
	}

	public function edit(PageData $data): void
	{
		$this->titleEnglish = $data->titleEnglish;
		$this->titleCzech = $data->titleCzech;
		$this->contentEnglish = $data->contentEnglish;
		$this->contentCzech = $data->contentCzech;
		$this->routeCzech = Strings::webalize($this->titleCzech);
		$this->routeEnglish = Strings::webalize($this->titleEnglish);
		$this->editedAt = new DateTime();
	}

	public function getData(): PageData
	{
		$data = new PageData();
		$data->titleEnglish = $this->titleEnglish;
		$data->titleCzech = $this->titleCzech;
		$data->contentEnglish = $this->contentEnglish;
		$data->contentCzech = $this->contentCzech;
		$data->author = $this->author;

		return $data;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}
	
	public function getTitle(): string 
	{
		return LanguageStaticHolder::getLanguage()->getIso() === 'en' ? $this->getTitleEnglish() : $this->getTitleCzech();
	}
	
	public function getContent(): string 
	{
		return LanguageStaticHolder::getLanguage()->getIso() === 'en' ? $this->getContentEnglish() : $this->getContentCzech();
	}

	public function getTitleEnglish(): string
	{
		return $this->titleEnglish;
	}

	public function getTitleCzech(): string
	{
		return $this->titleCzech;
	}

	public function getContentEnglish(): string
	{
		return $this->contentEnglish;
	}

	public function getContentCzech(): string
	{
		return $this->contentCzech;
	}

	public function getAuthor(): Admin
	{
		return $this->author;
	}
	
	public function addView(): void
	{
		$this->views++;
	}

	public function getViews(): int
	{
		return $this->views;
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function getRouteCzech(): string
	{
		return $this->routeCzech;
	}

	public function getRouteEnglish(): string
	{
		return $this->routeEnglish;
	}

	public function changeThumbnail(?Image $thumbnail): void
	{
		$this->thumbnail = $thumbnail;
	}

	public function getThumbnail(): ?Image
	{
		return $this->thumbnail;
	}
}