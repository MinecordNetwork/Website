<?php

declare(strict_types=1);

namespace App\Model\Sms\Record;

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

    /** @ORM\Column(unique=true) */
    private string $externalId;
    
    /** @ORM\Column */
    private string $text;

    /** @ORM\Column */
    private string $operator;

    /** @ORM\Column */
    private string $shortcode;

    /** @ORM\Column */
    private string $phone;

    /** @ORM\Column */
    private string $country;

    /** @ORM\Column(nullable=true) */
    private ?string $answer;

    /** @ORM\Column */
    private bool $requireConfirmation;

    /** @ORM\Column */
    private int $attempt;

    /** @ORM\Column(nullable=true) */
    private ?DateTime $confirmedAt = null;

    /** @ORM\Column */
    private DateTime $sentAt;

    /** @ORM\Column */
    private DateTime $createdAt;

    public function __construct(UuidInterface $id, SmsRecordData $data)
    {
        $this->id = $id;
        $this->createdAt = new DateTime();
        $this->text = $data->text;
        $this->operator = $data->operator;
        $this->shortcode = $data->shortcode;
        $this->phone = $data->phone;
        $this->externalId = $data->externalId;
        $this->country = $data->country;
        $this->answer = $data->answer;
        $this->attempt = $data->attempt;
        $this->requireConfirmation = $data->requireConfirmation;
        $this->sentAt = $data->sentAt;    
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

    public function getOperator(): string
    {
        return $this->operator;
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

    public function requireConfirmation(): bool
    {
        return $this->requireConfirmation;
    }

    public function getConfirmedAt(): DateTime
    {
        return $this->confirmedAt;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmedAt !== null;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setupAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
