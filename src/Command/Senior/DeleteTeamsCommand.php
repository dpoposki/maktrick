<?php

namespace App\Command\Senior;

use App\Repository\TeamRepository;
use App\Factory\CHPPFactory;
use Doctrine\ORM\EntityManagerInterface;
use PHT\Config\Team;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteTeamsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var \PHT\PHT
     */
    private $chpp;

    /**
     * @param CHPPFactory $chpp
     * @param EntityManagerInterface $entityManager
     * @param TeamRepository $teamRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TeamRepository $teamRepository, CHPPFactory $chpp)
    {
        $this->entityManager = $entityManager;
        $this->teamRepository = $teamRepository;

        $this->chpp = $chpp->build();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('senior:teams:delete')
            ->setDescription('Deletes senior teams')
            ->setHelp('This command allows you to delete non-existing entries from the full list of senior teams found in the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $teams = $this->teamRepository->findAll();

        foreach ($teams as $team) {
            $configTeam = new Team();
            $configTeam->id = $team->getId();

            $chppTeam = $this->chpp->getSeniorTeam($configTeam);

            if ($chppTeam->isBot() || $chppTeam->isDeleted()) {
                $this->entityManager->remove($team);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
