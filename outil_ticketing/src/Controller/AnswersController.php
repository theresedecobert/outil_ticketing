<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Tickets;
use App\Form\AnswersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/answers')]
class AnswersController extends AbstractController
{
    
    #[Route('/new/{id}', name: 'app_answers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Assume you have a method to find the ticket by ID in your repository
        $ticket = $entityManager->getRepository(Tickets::class)->find($id);

        $answer = new Answers();

        // Getting the connected user
        $user = $this->getUser();

        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associating the answer with the user
            $answer->setUser($user);
            $answer->setTicket($ticket);
            $entityManager->persist($answer);
            $entityManager->flush();
        
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }
}
