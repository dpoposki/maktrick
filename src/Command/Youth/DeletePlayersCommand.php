<?php

namespace App\Command\Youth;

use App\Repository\YouthPlayerRepository;
use App\Factory\CHPPFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeletePlayersCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var YouthPlayerRepository
     */
    private $youthPlayerRepository;

    /**
     * @var \PHT\PHT
     */
    private $chpp;

    /**
     * @param EntityManagerInterface $entityManager
     * @param YouthPlayerRepository $youthPlayerRepository
     * @param CHPPFactory $chpp
     */
    public function __construct(EntityManagerInterface $entityManager, YouthPlayerRepository $youthPlayerRepository, CHPPFactory $chpp)
    {
        $this->entityManager = $entityManager;
        $this->youthPlayerRepository = $youthPlayerRepository;

        $this->chpp = $chpp->build();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('youth:players:delete')
            ->setDescription('Deletes youth players')
            ->setHelp('This command allows you to delete non-existing entries from the full list of youth players found in the database')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $youthPlayers = $this->youthPlayerRepository->findAll();

        foreach ($youthPlayers as $youthPlayer) {
            try {
                $this->chpp->getYouthPlayer($youthPlayer->getId());
            } catch (\Exception $exception) {
                // Player does not exist, we can remove it
                $this->entityManager->remove($youthPlayer);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
