<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Tickets;
use App\Form\AnswersType;
use App\Repository\AnswersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/answers')]
class AnswersController extends AbstractController
{
    #[Route('/', name: 'app_answers_index', methods: ['GET'])]
    public function index(AnswersRepository $answersRepository): Response
    {
        return $this->render('answers/index.html.twig', [
            'answers' => $answersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_answers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $answer = new Answers();

        // Getting the connected user
        $user = $this->getUser();

        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associating the answer with the user
            $answer->setUser($user);

            $entityManager->persist($answer);
            $entityManager->flush();
            dd($answer);
            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_answers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Answers $answer, EntityManagerInterface $entityManager, Security $security): Response
    {

        // Set the connected user as the author of the answer
        $user = $security->getUser();
        $isAuthor = $user === $answer->getUser();

        $form = $this->createForm(AnswersType::class, $answer, [
            'is_author' => $isAuthor,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answers_delete', methods: ['POST'])]
    public function delete(Request $request, Answers $answer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $answer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
