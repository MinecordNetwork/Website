<?php

declare(strict_types=1);

namespace Minecord\Module\Console\Admin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Minecord\Model\Admin\AdminData;
use Minecord\Model\Admin\AdminFacade;

final class CreateAdminCommand extends Command
{
	/** @var string */
	public static $defaultName = 'minecord:admin:create';

	private AdminFacade $adminFacade;

	public function __construct(
		AdminFacade $adminFacade
	) {
		parent::__construct();
		$this->adminFacade = $adminFacade;
	}

	public function execute(InputInterface $input, OutputInterface $output): int
	{
		/** @var QuestionHelper $questionHelper */
		$questionHelper = $this->getHelper('question');

		$adminData = new AdminData();
		$adminData->displayName = (string) $questionHelper->ask($input, $output, new Question('Admin display name: '));
		$adminData->email = (string) $questionHelper->ask($input, $output, new Question('Admin email: '));
		$adminData->password = (string) $questionHelper->ask($input, $output, new Question('Admin password: '));

		$admin = $this->adminFacade->create($adminData);

		$output->writeln('<info>New administrator "' . $admin->getDisplayName() . '" was created!</info>');
		
		return 1;
	}
}
