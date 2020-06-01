<?php

declare(strict_types=1);

namespace Minecord\Model\Session;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Nette\Http\Session as NetteSession;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Rixafy\IpAddress\Exception\IpAddressNotFoundException;
use Rixafy\IpAddress\IpAddressFacade;
use Rixafy\IpAddress\IpAddressFactory;
use Minecord\Model\Session\Exception\SessionNotFoundException;

class SessionProvider
{
	const SESSION_KEY = 'sessionHolder';

	private SessionFacade $sessionFacade;
	private IpAddressFacade $ipAddressFacade;
	private IpAddressFactory $ipAddressFactory;
	private NetteSession $netteSession;
	private Session $session;

	public function __construct(
		SessionFacade $sessionFacade,
		NetteSession $netteSession,
		IpAddressFacade $ipAddressFacade,
		IpAddressFactory $ipAddressFactory
	) {
		$this->sessionFacade = $sessionFacade;
		$this->netteSession = $netteSession;
		$this->ipAddressFacade = $ipAddressFacade;
		$this->ipAddressFactory = $ipAddressFactory;
	}

	public function setup(): void
	{
		$section = $this->netteSession->getSection(self::SESSION_KEY);

		if (($uuidString = $section->{self::SESSION_KEY}) !== null) {
			try {
				$this->session = $this->sessionFacade->get(Uuid::fromString($uuidString));

			} catch (SessionNotFoundException $e) {
				unset($section->{self::SESSION_KEY});
				$this->setup();
			}

		} else {
			$crawlerDetect = new CrawlerDetect();

			$sessionData = new SessionData();
			$sessionData->hash = $this->netteSession->getId();
			$sessionData->isCrawler = $crawlerDetect->isCrawler();

			if ($sessionData->isCrawler) {
				session_destroy();
				session_id($newHash = substr(md5(Strings::webalize($crawlerDetect->getMatches())), 0, 26));
				session_start();
				$sessionData->hash = $newHash;
				$sessionData->crawlerName = $crawlerDetect->getMatches();
			}

			try {
				$sessionData->ipAddress = $this->ipAddressFacade->getByAddress($_SERVER['REMOTE_ADDR']);

			} catch (IpAddressNotFoundException $e) {
				$sessionData->ipAddress = $this->ipAddressFactory->create($_SERVER['REMOTE_ADDR']);
			}
			
			if ($sessionData->isCrawler) {
				try {
					$this->session = $this->sessionFacade->getByHash($sessionData->hash);

				} catch (SessionNotFoundException $e) {
					$this->session = $this->sessionFacade->create($sessionData);
				}
			} else {
				$this->session = $this->sessionFacade->create($sessionData);
			}
			
			$section->{self::SESSION_KEY} = (string) $this->session->getId();
		}
	}

	public function provide(): Session
	{
		return $this->session;
	}
}
