<?php

namespace App\Controller;

use App\Entity\Recompense;
use App\Form\RecompenseType;
use App\Repository\RecompenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recompense')]
final class RecompenseController extends AbstractController
{
    #[Route(name: 'app_recompense_index', methods: ['GET'])]
    public function index(RecompenseRepository $recompenseRepository): Response
    {
        return $this->render('recompense/index.html.twig', [
            'recompenses' => $recompenseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recompense_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recompense = new Recompense();
        $form = $this->createForm(RecompenseType::class, $recompense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recompense);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Recompense created successfully.');

            return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recompense/new.html.twig', [
            'recompense' => $recompense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recompense_show', methods: ['GET'])]
    public function show(Recompense $recompense): Response
    {
        return $this->render('recompense/show.html.twig', [
            'recompense' => $recompense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recompense_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function edit(Request $request, Recompense $recompense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecompenseType::class, $recompense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Recompense updated successfully.');

            return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recompense/edit.html.twig', [
            'recompense' => $recompense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recompense_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restrict access to admins
    public function delete(Request $request, Recompense $recompense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recompense->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recompense);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Recompense deleted successfully.');
        } else {
            // Add a flash message for invalid CSRF token
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
    }
}