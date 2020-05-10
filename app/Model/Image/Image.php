<?php

declare(strict_types=1);

namespace Minecord\Model\Image;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\DoctrineTraits\DateTimeTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="image", indexes={
 * 		@ORM\Index(columns={"original_name"})
 *	 })
 */
class Image
{
	public const TYPE_HTML_EDITOR = 1;
	public const TYPE_ARTICLE = 2;
	public const TYPES = [
		self::TYPE_HTML_EDITOR => 'html-editor',
		self::TYPE_ARTICLE => 'article'
	];

	/**
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	protected UuidInterface $id;

	/** @ORM\Column(type="string", length=63) */
	private string $originalName;

	/** @ORM\Column(type="string", length=5) */
	private string $extension;

	/** @ORM\Column(type="smallint") */
	private int $type;

	/** @ORM\Column(type="string", unique=true, length=255) */
	private string $path;

	/** @ORM\Column(type="string", unique=true, length=255) */
	private string $publicPath;

	/** @ORM\Column(type="string", length=127, nullable=true) */
	private ?string $caption;

	/** @ORM\Column(type="string", length=127, nullable=true) */
	private ?string $alternativeText;

	use DateTimeTrait;

	public function __construct(UuidInterface $id, ImageData $imageData)
	{
		$this->id = $id;
		$this->type = $imageData->type;
		$this->extension = pathinfo($imageData->originalName, PATHINFO_EXTENSION);
		$this->createdAt = new DateTime();
		$this->edit($imageData);
	}

	public function edit(ImageData $imageData): void
	{
		$this->alternativeText = $imageData->alternativeText;
		$this->caption = $imageData->caption;
		$this->originalName = $imageData->originalName;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getData(): ImageData
	{
		$data = new ImageData();
		$data->caption = $this->caption;
		$data->alternativeText = $this->alternativeText;
		$data->originalName = $this->originalName;

		return $data;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getPublicPath(): string
	{
		return $this->publicPath;
	}

	public function onFileSave(string $path, string $publicPath): void
	{
		$this->path = $path;
		$this->publicPath = $publicPath;
	}

	public function getCaption(): ?string
	{
		return $this->caption;
	}

	public function getAlternativeText(): ?string
	{
		return $this->alternativeText;
	}

	public function getOriginalName(): string
	{
		return $this->originalName;
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function getExtension(): string
	{
		return $this->extension;
	}
}
