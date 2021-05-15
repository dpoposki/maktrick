<?php

namespace App\Command\Senior;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Factory\CHPPFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTeamsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var \PHT\PHT
     */
    private $chpp;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param CHPPFactory $chpp
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, CHPPFactory $chpp)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;

        $this->chpp = $chpp->build();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('senior:teams:update')
            ->setDescription('Updates senior teams')
            ->setHelp('This command allows you to update the full list of senior teams found in the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = [];

        foreach ($this->userRepository->findAll() as $user) {
            $users[$user->getId()] = $user;
        }

        $leagues = [
            [ 60147, 60147 ], // First league
            [ 60156, 60159 ], // Second league
            [ 60256, 60271 ], // Third league
            [ 115432, 115495 ] // Fourth league
        ];

        foreach ($leagues as $league) {
            // go through every league in the level
            for ($i = $league[0]; $i <= $league[1]; $i++) {
                $chppLeague = $this->chpp->getSeniorLeague($i);

                // go through every team in the league
                for ($j = 0; $j < 8; $j++) {
                    $chppTeam = $chppLeague->getTeam($j)->getTeam();

                    if ($chppTeam->isBot() || $chppTeam->isDeleted()) {
                        continue;
                    }

                    // get user by ID
                    $user = isset($users[$chppTeam->getUserId()]) ? $users[$chppTeam->getUserId()] : null;

                    if ($user === null) {
                        $user = new User();
                        $user->setId($chppTeam->getUserId());

                        $users[$user->getId()] = $user;
                    }

                    $team = $user->getTeam($chppTeam->getId());

                    if (!$team instanceof Team) {
                        $team = new Team();
                        $team->setUser($user);
                    }

                    $team->setId($chppTeam->getId());
                    $team->setName($chppTeam->getName());

                    $this->entityManager->persist($team);
                }
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
