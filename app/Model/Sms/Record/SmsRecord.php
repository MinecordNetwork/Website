<?php

declare(strict_types=1);

namespace Minecord\Model\Sms\Record;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="sms_record")
 */
class SmsRecord
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	private UuidInterface $id;

	/** @ORM\Column(type="string", unique=true) */
	private string $externalId;
	
	/** @ORM\Column(type="string") */
	private string $text;

	/** @ORM\Column(type="string") */
	private string $provider;

	/** @ORM\Column(type="string") */
	private string $shortcode;

	/** @ORM\Column(type="string") */
	private string $phone;

	/** @ORM\Column(type="boolean") */
	private bool $requireConfirmation = false;

	/** @ORM\Column(type="datetime", nullable=true) */
	private ?DateTime $confirmedAt = null;

	/** @ORM\Column(type="datetime") */
	private DateTime $createdAt;

	public function __construct(UuidInterface $id, SmsRecordData $data)
	{
		$this->id = $id;
		$this->createdAt = new DateTime();
		$this->edit($data);
	}

	public function edit(SmsRecordData $data): void
	{
		$this->text = $data->text;
		$this->provider = $data->provider;
		$this->shortcode = $data->shortcode;
		$this->phone = $data->phone;
		$this->externalId = $data->externalId;
		$this->requireConfirmation = $data->requireConfirmation;
	}
	
	public function onConfirm(): void
	{
		$this->confirmedAt = new DateTime();
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function getProvider(): string
	{
		return $this->provider;
	}

	public function getShortcode(): string
	{
		return $this->shortcode;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function getExternalId(): string
	{
		return $this->externalId;
	}

	public function isRequireConfirmation(): bool
	{
		return $this->requireConfirmation;
	}

	public function getConfirmedAt(): DateTime
	{
		return $this->confirmedAt;
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}
}
