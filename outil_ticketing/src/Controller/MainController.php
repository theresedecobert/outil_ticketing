<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AnswersRepository;
use App\Repository\TicketsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'home')]
    public function index(TicketsRepository $ticketsRepository, AnswersRepository $answersRepository, UserRepository $userRepository): Response
    {
        // Comptage du nombre de tickets ouverts
        $openedTicketsCount = $ticketsRepository->countByStatus('Ouvert');

        // Comptage du nombre de tickets résolus (fermés)
        $closedTicketsCount = $ticketsRepository->countByStatus('Fermé');

        // Recherche du meilleur technicien (celui qui a répondu au plus de tickets)
        $bestTechnician = $userRepository->findBestTechnician();

        return $this->render('main/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
            'answers' => $answersRepository->findAll(),
            'users' => $userRepository->findAll(),
            'openedTicketsCount' => $openedTicketsCount,
            'closedTicketsCount' => $closedTicketsCount,
            'bestTechnician' => $bestTechnician,
        ]);
    }
}
