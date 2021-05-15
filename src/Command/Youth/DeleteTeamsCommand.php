<?php

namespace App\Command\Youth;

use App\Repository\YouthTeamRepository;
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
     * @var YouthTeamRepository
     */
    private $youthTeamRepository;

    /**
     * @var \PHT\PHT
     */
    private $chpp;

    /**
     * @param EntityManagerInterface $entityManager
     * @param YouthTeamRepository $youthTeamRepository
     * @param CHPPFactory $chpp
     */
    public function __construct(EntityManagerInterface $entityManager, YouthTeamRepository $youthTeamRepository, CHPPFactory $chpp)
    {
        $this->entityManager = $entityManager;
        $this->youthTeamRepository = $youthTeamRepository;

        $this->chpp = $chpp->build();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('youth:teams:delete')
            ->setDescription('Deletes youth teams')
            ->setHelp('This command allows you to delete non-existing entries from the full list of youth teams found in the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $youthTeams = $this->youthTeamRepository->findAll();

        foreach ($youthTeams as $youthTeam) {
            $configTeam = new Team();
            $configTeam->id = $youthTeam->getId();

            $chppYouthTeam = $this->chpp->getYouthTeam($configTeam);

            if ($chppYouthTeam->isDeleted()) {
                $this->entityManager->remove($youthTeam);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
