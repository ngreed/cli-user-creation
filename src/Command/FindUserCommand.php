<?php

namespace App\Command;

use App\Service\User\UserManager;
use App\Service\User\UserManagerMessageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindUserCommand extends Command
{
    protected static $defaultName = 'app:user:find';

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

    protected function configure(): void
    {
        $this
            ->setDescription("Displays user's data")
            ->setHelp("This command allows you to display user's data.")
            ->addArgument('email', InputArgument::REQUIRED, "User's Email Address");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            $this->messageProvider->getFindMessage(
                $this->userManager->find(
                    $input->getArgument('email')
                )
            )
        );

        return Command::SUCCESS;
    }
}