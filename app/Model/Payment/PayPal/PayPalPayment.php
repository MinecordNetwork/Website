<?php

declare(strict_types=1);

namespace App\Model\Payment\PayPal;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Model\Product\Product;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Currency\Currency;
use Rixafy\DoctrineTraits\DateTimeTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="paypal_payment")
 */
class PayPalPayment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private UuidInterface $id;

    /** @ORM\Column(nullable=true) */
    private ?string $email = null;

    /** @ORM\Column */
    private string $nickname;

    /** @ORM\Column */
    private float $price;

    /** @ORM\ManyToOne(targetEntity="\Rixafy\Currency\Currency") */
    private Currency $currency;

    /** @ORM\ManyToOne(targetEntity="\App\Model\Product\Product") */
    private Product $product;

    /** @ORM\Column */
    private bool $accepted = false;

    /** @ORM\Column(nullable=true) */
    private ?DateTime $acceptedAt = null;

    use DateTimeTrait;

    public function __construct(UuidInterface $id, PayPalPaymentData $data)
    {
        $this->id = $id;
        $this->price = $data->price;
        $this->currency = $data->currency;
        $this->nickname = $data->nickname;
        $this->product = $data->product;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function onAccept(string $email): void
    {
        $this->email = $email;
        $this->accepted = true;
        $this->acceptedAt = new DateTime();
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
