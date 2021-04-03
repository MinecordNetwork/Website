<?php

declare(strict_types=1);

namespace App\Model\Article;

use App\Model\User\UserProvider;

final class ArticleDataFactory
{
    public function __construct(
        private UserProvider $userProvider
    ) {}

    public function createFromFormData(array $formData): ArticleData
    {
        $data = new ArticleData();
        $data->titleEnglish = $formData['titleEnglish'];
        $data->titleCzech = $formData['titleCzech'];
        $data->contentEnglish = $formData['contentEnglish'];
        $data->contentCzech = $formData['contentCzech'];
        $data->editorialEnglish = $formData['editorialEnglish'];
        $data->editorialCzech = $formData['editorialCzech'];
        $data->author = $this->userProvider->provide();

        return $data;
    }
}
