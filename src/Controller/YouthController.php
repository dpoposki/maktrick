<?php

namespace App\Controller;

use App\Repository\YouthPlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YouthController extends AbstractController
{
    /**
     * @Route("/youth", name="youth.index")
     * @param YouthPlayerRepository $youthPlayerRepository
     * @return Response
     */
    public function index(YouthPlayerRepository $youthPlayerRepository): Response
    {
        $players = [];

        foreach ($youthPlayerRepository->findAll() as $player) {
            if ($player->isEligible()) {
                $players[] = $player;
            }
        }

        return $this->render('youth/index.html.twig', [
            'players' => $players
        ]);
    }
}
