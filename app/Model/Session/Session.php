<?php

declare(strict_types=1);

namespace App\Model\Session;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\IpAddress\IpAddress;
use App\Model\User\User;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="session", uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"hash"})
 * })
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private UuidInterface $id;

    /** @ORM\Column(length=26, unique=true) */
    private string $hash;

    /** @ORM\Column */
    private bool $isCrawler;

    /** @ORM\Column(length=63, nullable=true) */
    private ?string $crawlerName;

    /** @ORM\ManyToOne(targetEntity="\Rixafy\IpAddress\IpAddress", inversedBy="session", cascade={"persist"}) */
    private IpAddress $ipAddress;

    /** @ORM\ManyToOne(targetEntity="\App\Model\User\User", inversedBy="session", cascade={"persist"}) */
    private ?User $user = null;

    use DateTimeTrait;

    public function __construct(UuidInterface $id, SessionData $data)
    {
        $this->id = $id;
        $this->hash = $data->hash;
        $this->isCrawler = $data->isCrawler;
        $this->crawlerName = $data->crawlerName;
        $this->edit($data);
    }

    public function edit(SessionData $data): void
    {
        $this->ipAddress = $data->ipAddress;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getData(): SessionData
    {
        $data = new SessionData();
        $data->ipAddress = $this->ipAddress;
        return $data;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getIpAddress(): IpAddress
    {
        return $this->ipAddress;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function authenticateUser(User $user): void
    {
        $this->user = $user;
        $this->user->onLogin();
    }

    public function logOutUser(): void
    {
        $this->user = null;
    }

    public function isCrawler(): bool
    {
        return $this->isCrawler;
    }

    public function getCrawlerName(): string
    {
        return $this->crawlerName;
    }
}
