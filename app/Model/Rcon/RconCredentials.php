<?php

declare(strict_types=1);

namespace App\Model\Rcon;

class RconCredentials
{
    public function __construct(
        private string $password,
        private string $proxyHost,
        private int $proxyRconPort
    ) {}

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProxyHost(): string
    {
        return $this->proxyHost;
    }

    public function getProxyRconPort(): int
    {
        return $this->proxyRconPort;
    }
}
