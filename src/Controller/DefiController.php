<?php

namespace App\Controller;

use App\Entity\Defi;
use App\Entity\User;
use App\Form\DefiType;
use App\Repository\DefiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/defi')]
final class DefiController extends AbstractController
{
    // Display all Defis for users
    #[Route('', name: 'app_user_defis', methods: ['GET'])]
    public function index(DefiRepository $defiRepository): Response
    {
        return $this->render('defi/user/index.html.twig', [
            'defis' => $defiRepository->findAll(),
        ]);
    }

    // Show a specific Defi for users
    #[Route('/{id}', name: 'app_user_defi_show', methods: ['GET'])]
    public function show(Defi $defi): Response
    {
        return $this->render('defi/user/show.html.twig', [
            'defi' => $defi,
        ]);
    }

    // Complete a Defi (User action)
    #[Route('/{id}/complete', name: 'app_user_defi_complete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')] // Only logged-in users can complete a Defi
    public function complete(Defi $defi, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        // Check if the user has already completed this Defi
        $existingProgress = $entityManager->getRepository(Progression::class)
            ->findOneBy(['user' => $user, 'defi' => $defi]);

        if ($existingProgress) {
            $this->addFlash('error', 'You have already completed this challenge!');
        } else {
            // Save the user's progress and mark the Defi as completed
            $progression = new Progression();
            $progression->setUser($user);
            $progression->setDefi($defi);
            $progression->setCompletedAt(new \DateTime());

            $entityManager->persist($progression);
            $entityManager->flush();

            // Optionally, give points or rewards here
            $this->addFlash('success', 'Defi completed successfully!');
        }

        return $this->redirectToRoute('app_user_defis');
    }

    // Admin: List all Defis
    #[Route('/admin', name: 'app_admin_defi_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')] // Only admins can access this page
    public function adminIndex(DefiRepository $defiRepository): Response
    {
        return $this->render('defi/admin/index.html.twig', [
            'defis' => $defiRepository->findAll(),
        ]);
    }

    // Admin: Create a new Defi
    #[Route('/new', name: 'app_admin_defi_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Only admins can access this page
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $defi = new Defi();
        $form = $this->createForm(DefiType::class, $defi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($defi);
            $entityManager->flush();

            $this->addFlash('success', 'Defi created successfully.');

            return $this->redirectToRoute('app_admin_defi_index');
        }

        return $this->render('defi/admin/new.html.twig', [
            'defi' => $defi,
            'form' => $form->createView(),
        ]);
    }

    // Admin: Edit an existing Defi
    #[Route('/{id}/edit', name: 'app_admin_defi_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')] // Only admins can access this page
    public function edit(Request $request, Defi $defi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefiType::class, $defi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Defi updated successfully.');

            return $this->redirectToRoute('app_admin_defi_index');
        }

        return $this->render('defi/admin/edit.html.twig', [
            'defi' => $defi,
            'form' => $form->createView(),
        ]);
    }

    // Admin: Delete a Defi
    #[Route('/{id}/delete', name: 'app_admin_defi_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Only admins can access this page
    public function delete(Request $request, Defi $defi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $defi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($defi);
            $entityManager->flush();

            $this->addFlash('success', 'Defi deleted successfully.');
        }

        return $this->redirectToRoute('app_admin_defi_index');
    }
}
