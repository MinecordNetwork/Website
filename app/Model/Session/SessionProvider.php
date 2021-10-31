<?php

declare(strict_types=1);

namespace App\Model\Session;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Nette\Http\Session as NetteSession;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Rixafy\IpAddress\Exception\IpAddressNotFoundException;
use Rixafy\IpAddress\IpAddressFacade;
use Rixafy\IpAddress\IpAddressFactory;
use App\Model\Session\Exception\SessionNotFoundException;

class SessionProvider
{
    const SESSION_KEY = 'sessionHolder';

    private Session $session;

    public function __construct(
        private SessionFacade $sessionFacade,
        private NetteSession $netteSession,
        private IpAddressFacade $ipAddressFacade,
        private IpAddressFactory $ipAddressFactory
    ) {}

    public function setup(): void
    {
        $section = $this->netteSession->getSection(self::SESSION_KEY);

        if (($uuidString = $section->get(self::SESSION_KEY)) !== null) {
            try {
                $this->session = $this->sessionFacade->get(Uuid::fromString($uuidString));

            } catch (SessionNotFoundException) {
                $section->remove(self::SESSION_KEY);
                $this->setup();
            }

        } else {
            $crawlerDetect = new CrawlerDetect();

            $sessionData = new SessionData();
            $sessionData->hash = $this->netteSession->getId();
            $sessionData->isCrawler = $crawlerDetect->isCrawler();

            if ($sessionData->isCrawler) {
                if (session_status() === PHP_SESSION_ACTIVE) {
                    $this->netteSession->destroy();
                }
                session_id($newHash = substr(md5(Strings::webalize($crawlerDetect->getMatches())), 0, 26));
                $this->netteSession->start();
                $sessionData->hash = $newHash;
                $sessionData->crawlerName = $crawlerDetect->getMatches();
            }

            try {
                $sessionData->ipAddress = $this->ipAddressFacade->getByAddress($_SERVER['REMOTE_ADDR']);

            } catch (IpAddressNotFoundException) {
                $sessionData->ipAddress = $this->ipAddressFactory->create($_SERVER['REMOTE_ADDR']);
            }
            
            if ($sessionData->isCrawler) {
                try {
                    $this->session = $this->sessionFacade->getByHash($sessionData->hash);

                } catch (SessionNotFoundException) {
                    $this->session = $this->sessionFacade->create($sessionData);
                }
            } else {
                $this->session = $this->sessionFacade->create($sessionData);
            }
            
            $section->set(self::SESSION_KEY, (string) $this->session->getId());
        }
    }

    public function provide(): Session
    {
        return $this->session;
    }
}
