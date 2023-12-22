<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Tickets;
use App\Form\TicketsType;
use App\Service\pictureService;
use App\Repository\AnswersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tickets')]
class TicketsController extends AbstractController
{

    #[Route('/new', name: 'app_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, pictureService $pictureService): Response
    {
        $ticket = new Tickets();

        // Set the connected user as the author of the ticket
        $user = $this->getUser();
        $ticket->setUser($user);

        // Set the status on open
        $ticket->setStatus('Ouvert');

        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Getting pictures
            $files = $form->get('files')->getData();

            foreach ($files as $file) {
                // Setting destination folder
                $folder = 'tickets';
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
    public function show(Tickets $ticket, AnswersRepository $answersRepository): Response
    {

        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
            'answers' => $answersRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tickets $ticket, EntityManagerInterface $entityManager, pictureService $pictureService): Response
    {

        // Getting connected user
        $user = $this->getUser();

        // Checking if user is the author of the ticket
        $isAuthor = $user === $ticket->getUser();

        // Getting roles
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $form = $this->createForm(TicketsType::class, $ticket, [
            // 'is_admin' => $isAdmin,
            // 'is_author' => $isAuthor,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Getting pictures
            $files = $form->get('files')->getData();

            foreach ($files as $file) {
                // Setting destination folder
                $folder = 'tickets';
                $fichier = $pictureService->add($file, $folder, 300, 300);

                $file = new Files();
                $file->setFile($fichier);
                $ticket->addFile($file);
            }

            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Tickets $ticket, EntityManagerInterface $entityManager): Response
    {

        // Getting connected user
        $user = $this->getUser();

        // Checking if user is the author of the ticket
        $isAuthor = $user === $ticket->getUser();

        // Getting roles
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
