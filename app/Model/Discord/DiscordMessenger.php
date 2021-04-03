<?php

declare(strict_types=1);

namespace App\Model\Discord;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Model\Article\Article;
use Nette\Utils\Json;

class DiscordMessenger
{
    public function __construct(
        private string $englishWebhook,
        private string $czechWebhook
    ) {}

    public function notifyArticle(Article $article): void
    {
        $this->notify(
            $this->czechWebhook, 
            "Na web byl napsán nový příspěvek! @here \nhttps://minecord.cz/blog/" . $article->getRouteCzech(),
            'Minecord.cz',
            'https://minecord.net/img/discord_avatar.png'
        );
            
        $this->notify(
            $this->englishWebhook, 
            "New article was just published! @here \nhttps://minecord.net/blog/" . $article->getRouteEnglish(),
            'Minecord.net',
            'https://minecord.net/img/discord_avatar.png'
        );
    }
    
    private function notify(string $webhook, string $message, string $nickname, string $avatar): void
    {
        (new Client())->send(new Request('POST', $webhook, [
            'Content-type' => 'application/json'
        ], Json::encode([
            'username' => $nickname,
            'avatar_url' => $avatar,
            'content' => $message
        ])));
    }
}
