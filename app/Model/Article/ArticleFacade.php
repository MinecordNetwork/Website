<?php

declare(strict_types=1);

namespace App\Model\Article;

use Doctrine\ORM\EntityManagerInterface;
use App\Model\Article\Exception\ArticleNotFoundException;
use App\Model\Discord\DiscordMessenger;
use App\Model\Image\Image;
use Ramsey\Uuid\UuidInterface;

final class ArticleFacade extends ArticleRepository
{
    public function __construct(
        private ArticleFactory $articleFactory,
        private EntityManagerInterface $entityManager,
        private DiscordMessenger $discordMessenger
    ) {
        parent::__construct($entityManager);
    }

    public function create(ArticleData $data): Article
    {
        $article = $this->articleFactory->create($data);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function edit(UuidInterface $id, ArticleData $data): Article
    {
        $article = $this->get($id);

        $article->edit($data);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function changeThumbnail(UuidInterface $id, Image $image): Article
    {
        $article = $this->get($id);

        $article->changeThumbnail($image);
        $this->entityManager->flush();

        return $article;
    }

    public function notifyDiscord(): void
    {
        $articles = $this->getAllByNotifiedDiscord(false);
        
        foreach ($articles as $article) {
            $this->discordMessenger->notifyArticle($article);
            $article->onDiscordNotify();
        }

        $this->entityManager->flush();
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function delete(UuidInterface $id): void
    {
        $article = $this->get($id);

        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }
}
