<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Nette\Security\Passwords;
use Ramsey\Uuid\UuidInterface;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\RemovableTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="admin")
 */
class Admin
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", unique=true)
	 */
	private UuidInterface $id;

	/** @ORM\Column(type="string", length=127) */
	private string $displayName;

	/** @ORM\Column(type="string", length=127) */
	private string $email;

	/** @ORM\Column(type="string", length=255) */
	private string $password;

	/** @ORM\Column(type="string", length=127, nullable=true) */
	private ?string $googleId = null;

	/** @ORM\Column(type="string", length=127, nullable=true) */
	private ?string $facebookId = null;

	/** @ORM\Column(type="string", length=127, nullable=true) */
	private ?string $twitterId = null;

	/** @ORM\Column(type="datetime", nullable=true) */
	private ?DateTime $lastLoginAt = null;

	/** @ORM\Column(type="datetime", nullable=true) */
	private ?DateTime $firstLoginAt = null;

	use RemovableTrait;
	use DateTimeTrait;

	public function __construct(UuidInterface $id, AdminData $adminData, Passwords $passwords)
	{
		$this->id = $id;
		$this->edit($adminData, $passwords);
	}

	public function edit(AdminData $adminData, Passwords $passwords = null): void
	{
		$this->displayName = $adminData->displayName;
		$this->email = $adminData->email;

		if ($adminData->password !== null && $passwords !== null) {
			$this->changePassword($adminData->password, $passwords);
		}
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getData(): AdminData
	{
		$data = new AdminData();

		$data->displayName = $this->displayName;
		$data->email = $this->email;

		return $data;
	}

	public function getDisplayName(): string
	{
		return $this->displayName;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function changePassword(string $plainText, Passwords $passwords): void
	{
		$this->password = $passwords->hash($plainText);
	}

	public function checkPassword(string $plainText, Passwords $passwords): bool
	{
		return $passwords->verify($plainText, $this->password);
	}

	public function onLogin(): void
	{
		if ($this->firstLoginAt === null) {
			$this->firstLoginAt = new DateTime();
		}
		$this->lastLoginAt = new DateTime();
	}

	public function getGoogleId(): string
	{
		return $this->googleId;
	}

	public function getFacebookId(): string
	{
		return $this->facebookId;
	}

	public function getTwitterId(): string
	{
		return $this->twitterId;
	}

	public function getAvatar(int $size = 90): string
	{
		return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->getEmail())) . '?d=' . urlencode('https://' . $_SERVER['SERVER_NAME'] . '/css/admin/img/default_avatar.png') . '&s=' . $size;
	}
}
