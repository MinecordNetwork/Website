<?php

declare(strict_types=1);

namespace App\Model\Page;

use App\Model\User\UserProvider;

final class PageDataFactory
{
    public function __construct(
        private UserProvider $userProvider
    ) {}

    public function createFromFormData(array $formData): PageData
    {
        $data = new PageData();
        $data->titleEnglish = $formData['titleEnglish'];
        $data->titleCzech = $formData['titleCzech'];
        $data->contentEnglish = $formData['contentEnglish'];
        $data->contentCzech = $formData['contentCzech'];
        $data->author = $this->userProvider->provide();

        return $data;
    }
}
