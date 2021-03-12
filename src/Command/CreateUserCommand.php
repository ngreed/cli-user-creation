<?php

namespace App\Command;

use App\Service\User\UserManager;
use App\Service\User\UserManagerMessageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';

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
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email Address')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'First Name')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Last Name')
            ->addArgument('phone', InputArgument::OPTIONAL, 'Phone Number')
            ->addArgument('phone2', InputArgument::OPTIONAL, 'Another Phone Number')
            ->addArgument('comment', InputArgument::OPTIONAL, 'Comments');
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
            $this->messageProvider->getCreateMessage(
                $this->userManager->create(
                    $input->getArgument('email'),
                    $input->getArgument('firstname'),
                    $input->getArgument('lastname'),
                    $input->getArgument('phone'),
                    $input->getArgument('phone2'),
                    $input->getArgument('comment')
                )
            )
        );

        return Command::SUCCESS;
    }
}