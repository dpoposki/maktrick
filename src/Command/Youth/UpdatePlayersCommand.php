<?php

namespace App\Command\Youth;

use App\Entity\YouthPlayer;
use App\Repository\YouthTeamRepository;
use App\Factory\CHPPFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePlayersCommand extends Command
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
            ->setName('youth:players:update')
            ->setDescription('Updates youth players')
            ->setHelp('This command allows you to update the full list of youth players found in the database')
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
            $chppYouthPlayers = $this->chpp->getYouthPlayers($youthTeam->getId())->getPlayers();

            foreach ($chppYouthPlayers as $chppYouthPlayer) {
                $youthPlayer = $youthTeam->getYouthPlayer($chppYouthPlayer->getId());

                if (!$youthPlayer instanceof YouthPlayer) {
                    $youthPlayer = new YouthPlayer();
                }

                $youthPlayer->setId($chppYouthPlayer->getId());
                $youthPlayer->setFirstName($chppYouthPlayer->getFirstName());
                $youthPlayer->setLastName($chppYouthPlayer->getLastName());
                $youthPlayer->setYears($chppYouthPlayer->getAge());
                $youthPlayer->setDays($chppYouthPlayer->getDays());
                $youthPlayer->setSpeciality($chppYouthPlayer->getSpecialty());
                $youthPlayer->setUpdatedAt(new \DateTime('now'));

                $chppMatch = $chppYouthPlayer->getLastMatch();

                if ($chppMatch !== null) {
                    if ($youthPlayer->getRating() <= $chppMatch->getRating()) {
                        $youthPlayer->setMatchId($chppMatch->getId());
                        $youthPlayer->setRating($chppMatch->getRating());
                        $youthPlayer->setPosition($chppMatch->getPositionCode());
                    }
                }

                $youthTeam->addYouthPlayer($youthPlayer);
            }

            $this->entityManager->persist($youthTeam);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
