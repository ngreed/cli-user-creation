<?php

namespace App\Command;

use App\Service\User\Import\UserImporterCsv;
use App\Service\User\UserManager;
use App\Service\User\UserManagerMessageProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCsvUserCommand extends Command
{
    protected static $defaultName = 'app:user:import-csv';

    private UserImporterCsv $userImporter;
	private UserManager $userManager;
	private UserManagerMessageProvider $messageProvider;

    /**
     * @param UserImporterCsv            $userImporter
     * @param UserManager                $userManager
     * @param UserManagerMessageProvider $messageProvider
     */
    public function __construct(
        UserImporterCsv $userImporter,
        UserManager $userManager,
        UserManagerMessageProvider $messageProvider
    ) {
        $this->userImporter = $userImporter;
        $this->userManager = $userManager;
        $this->messageProvider = $messageProvider;

        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Imports users from a csv file.')
            ->setHelp('This command allows you to import users from a csv file.')
            ->addArgument('filepath', InputArgument::REQUIRED, 'Path to csv import file');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln(
            $this->messageProvider->getImportMessage(
                $this->userManager->createMultiple(
                    $this->userImporter->getData($input->getArgument('filepath'))
                )
            )
        );

        return Command::SUCCESS;
    }
}