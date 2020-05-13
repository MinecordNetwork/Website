<?php

declare(strict_types=1);

namespace Minecord\Model\Article;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Minecord\Model\User\User;
use Minecord\Model\Image\Image;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\LanguageStaticHolder;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 */
class Article
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

	/** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $editorialEnglish;

	/** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
	private string $editorialCzech;

	/** @ORM\ManyToOne(targetEntity="\Minecord\Model\User\User") */
	private User $author;

	/** @ORM\ManyToOne(targetEntity="\Minecord\Model\Image\Image") */
	private ?Image $thumbnail = null;

	/** @ORM\Column(type="integer") */
	private int $views = 0;

	/** @ORM\Column(type="datetime") */
	private DateTime $createdAt;

	/** @ORM\Column(type="datetime") */
	private DateTime $editedAt;

	public function __construct(UuidInterface $id, ArticleData $data)
	{
		$this->id = $id;
		$this->author = $data->author;
		$this->createdAt = new DateTime();
		$this->edit($data);
	}

	public function edit(ArticleData $data): void
	{
		$this->titleEnglish = $data->titleEnglish;
		$this->titleCzech = $data->titleCzech;
		$this->contentEnglish = $data->contentEnglish;
		$this->contentCzech = $data->contentCzech;
		$this->editorialEnglish = $data->editorialEnglish;
		$this->editorialCzech = $data->editorialCzech;
		$this->routeCzech = Strings::webalize($this->titleCzech);
		$this->routeEnglish = Strings::webalize($this->titleEnglish);
		$this->editedAt = new DateTime();
	}

	public function getData(): ArticleData
	{
		$data = new ArticleData();
		$data->titleEnglish = $this->titleEnglish;
		$data->titleCzech = $this->titleCzech;
		$data->contentEnglish = $this->contentEnglish;
		$data->contentCzech = $this->contentCzech;
		$data->editorialEnglish = $this->editorialEnglish;
		$data->editorialCzech = $this->editorialCzech;
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
	
	public function getEditorial(): string 
	{
		return LanguageStaticHolder::getLanguage()->getIso() === 'en' ? $this->getEditorialEnglish() : $this->getEditorialCzech();
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

	public function getEditorialEnglish(): string
	{
		return $this->editorialEnglish;
	}

	public function getEditorialCzech(): string
	{
		return $this->editorialCzech;
	}

	public function getAuthor(): User
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
