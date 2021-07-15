<?php

declare(strict_types=1);

namespace App\Model\System;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\LanguageStaticHolder;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="system")
 */
class System
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private UuidInterface $id;

    /** @ORM\Column */
    private int $onlinePlayerCount = 0;

    /** @ORM\Column(type="text") */
    private string $onlinePlayerList;

    /** @ORM\Column */
    private int $discordEnglishMemberCount = 0;

    /** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
    private string $discordEnglishMemberList;

    /** @ORM\Column */
    private int $discordCzechMemberCount = 0;

    /** @ORM\Column(type="text", options={"collation":"utf8mb4_unicode_ci"}) */
    private string $discordCzechMemberList;

    public function __construct(UuidInterface $id, SystemData $data)
    {
        $this->id = $id;
        $this->edit($data);
    }

    public function edit(SystemData $data): void
    {
        $this->onlinePlayerCount = $data->onlinePlayerCount;
        $this->onlinePlayerList = $data->onlinePlayerList;
        $this->discordEnglishMemberCount = $data->discordEnglishMemberCount;
        $this->discordEnglishMemberList = $data->discordEnglishMemberList;
        $this->discordCzechMemberCount = $data->discordCzechMemberCount;
        $this->discordCzechMemberList = $data->discordCzechMemberList;
    }

    public function getData(): SystemData
    {
        $data = new SystemData();
        $data->onlinePlayerCount = $this->onlinePlayerCount;
        $data->onlinePlayerList = $this->onlinePlayerList;
        $data->discordEnglishMemberCount = $this->discordEnglishMemberCount;
        $data->discordEnglishMemberList = $this->discordEnglishMemberList;
        $data->discordCzechMemberCount = $this->discordCzechMemberCount;
        $data->discordCzechMemberList = $this->discordCzechMemberList;

        return $data;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOnlinePlayerCount(): int
    {
        return $this->onlinePlayerCount;
    }

    public function getOnlinePlayerList(): string
    {
        return $this->onlinePlayerList;
    }

    public function getDiscordMemberCount(): int
    {
        return LanguageStaticHolder::getLanguage()->getIso() === 'en' ? $this->discordEnglishMemberCount : $this->discordCzechMemberCount;
    }

    public function getDiscordMemberList(): string
    {
        return LanguageStaticHolder::getLanguage()->getIso() === 'en' ? $this->discordEnglishMemberList : $this->discordCzechMemberList;
    }
}
