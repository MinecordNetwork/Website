<?php

declare(strict_types=1);

namespace App\Model\Domain\Info;

class DomainInfo
{
    public function __construct(
        private string $primaryDomain,
        private string $secondaryDomain
    ) {}

    public function getPrimaryDomain(): string
    {
        return $this->primaryDomain;
    }

    public function getSecondaryDomain(): string
    {
        return $this->secondaryDomain;
    }
}
