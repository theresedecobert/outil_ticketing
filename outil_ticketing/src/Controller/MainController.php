<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AnswersRepository;
use App\Repository\TicketsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TicketsRepository $ticketsRepository, AnswersRepository $answersRepository, UserRepository $userRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
            'answers' => $answersRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}
