<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Answers;
use App\Entity\Tickets;
use App\Form\AnswersType;
use App\Form\TicketsType;
use App\Service\pictureService;
use App\Repository\AnswersRepository;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tickets')]
class TicketsController extends AbstractController
{
    #[Route('/', name: 'app_tickets_index', methods: ['GET'])]
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, pictureService $pictureService, Security $security): Response
    {
        $ticket = new Tickets();

        // Set the connected user as the author of the ticket
        $user = $security->getUser();
        $ticket->setUser($user);

        // Set the status on open
        $ticket->setStatus('ouvert');

        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on récupère les images
            $files = $form->get('files')->getData();

            foreach ($files as $file) {
                // on définit le dossier de destination
                $folder = 'tickets';
                //on appelle le service d'ajout
                $fichier = $pictureService->add($file, $folder, 300, 300);

                $file = new Files();
                $file->setFile($fichier);
                $ticket->addFile($file);
            } 

            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

  
    #[Route('/{id}', name: 'app_tickets_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Tickets $ticket, EntityManagerInterface $entityManager, AnswersRepository $answersRepository): Response
    {
        $answer = new Answers(); // Créez une nouvelle instance de Answers
        $answerForm = $this->createForm(AnswersType::class, $answer);
        $answerForm->handleRequest($request);
    
        if ($answerForm->isSubmitted() && $answerForm->isValid()) {
            // Associez la réponse au ticket, si nécessaire
            $answer->setTicket($ticket);
    
            // Enregistrez la réponse
            $entityManager->persist($answer);
            $entityManager->flush();
    
            // Redirection ou mise à jour de la vue
            return $this->redirectToRoute('app_tickets_show', ['id' => $ticket->getId()]);
        }
    
        $editForm = $this->createForm(TicketsType::class, $ticket);
        $editForm->handleRequest($request);
    
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_tickets_show', ['id' => $ticket->getId()]);
        }
    
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
            'editForm' => $editForm->createView(),
            'answerForm' => $answerForm->createView(),
            'answers' => $answersRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tickets $ticket, EntityManagerInterface $entityManager, Security $security): Response
    {

        // Récupérez l'utilisateur connecté
        $user = $security->getUser();

        // Vérifiez si l'utilisateur est l'auteur du ticket
        $isAuthor = $user === $ticket->getUser();

        // Récupérez le rôle de l'utilisateur
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $form = $this->createForm(TicketsType::class, $ticket, [
            'is_admin' => $isAdmin,
            'is_author' => $isAuthor,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

   
}
