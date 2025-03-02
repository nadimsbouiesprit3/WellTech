<?php

namespace App\Controller;

use App\Entity\Progression;
use App\Form\ProgressionType;
use App\Repository\ProgressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/progression')]
final class ProgressionController extends AbstractController
{
    #[Route(name: 'app_progression_index', methods: ['GET'])]
    public function index(ProgressionRepository $progressionRepository): Response
    {
        return $this->render('progression/index.html.twig', [
            'progressions' => $progressionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_progression_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $progression = new Progression();
        $form = $this->createForm(ProgressionType::class, $progression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($progression);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Progression created successfully.');

            return $this->redirectToRoute('app_progression_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progression/new.html.twig', [
            'progression' => $progression,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_progression_show', methods: ['GET'])]
    public function show(Progression $progression): Response
    {
        return $this->render('progression/show.html.twig', [
            'progression' => $progression,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_progression_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function edit(Request $request, Progression $progression, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgressionType::class, $progression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Progression updated successfully.');

            return $this->redirectToRoute('app_progression_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progression/edit.html.twig', [
            'progression' => $progression,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_progression_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function delete(Request $request, Progression $progression, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$progression->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($progression);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Progression deleted successfully.');
        } else {
            // Add a flash message for invalid CSRF token
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_progression_index', [], Response::HTTP_SEE_OTHER);
    }
}