<?php

namespace App\Command;

use App\Service\UserManager;
use App\Service\UserManagerMessageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportUserCommand extends Command
{
    protected static $defaultName = 'app:user:import';

	private UserManager $userManager;
	private UserManagerMessageProvider $messageProvider;

    /**
     * @param UserManager $userManager
     * @param UserManagerMessageProvider $messageProvider
     */
    public function __construct(
        UserManager $userManager,
        UserManagerMessageProvider $messageProvider
    ) {
        $this->userManager = $userManager;
        $this->messageProvider = $messageProvider;

        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Imports users.')
            ->setHelp('This command allows you to import users from a csv file.')
            ->addArgument('filepath', InputArgument::REQUIRED, 'Path to csv import file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln(
            $this->messageProvider->getImportMessage(
                $this->userManager->import(
                    $input->getArgument('filepath')
                )
            )
        );

        return Command::SUCCESS;
    }
}