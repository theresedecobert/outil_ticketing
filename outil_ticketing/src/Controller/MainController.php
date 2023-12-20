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
        // Comptage du nombre de tickets ouverts et fermés
        $openedTicketsCount = $ticketsRepository->countByStatus('Ouvert');
        $closedTicketsCount = $ticketsRepository->countByStatus('Fermé');

        // Recherche du meilleur technicien (celui qui a répondu au plus de tickets)
        $bestTechnician = $userRepository->findBestTechnician();

        // Récupération des tickets du plus récent au plus ancien
        $tickets = $ticketsRepository->findAllOrderedByDateDesc();

        return $this->render('main/index.html.twig', [
            'tickets' => $tickets, // Utilisation de la liste des tickets triée par date
            'answers' => $answersRepository->findAll(),
            'users' => $userRepository->findAll(),
            'openedTicketsCount' => $openedTicketsCount,
            'closedTicketsCount' => $closedTicketsCount,
            'bestTechnician' => $bestTechnician,
        ]);
    }
}