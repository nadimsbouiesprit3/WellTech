<?php

namespace App\Controller;

use App\Entity\Defi;
use App\Form\DefiType;
use App\Repository\DefiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/defi')]
final class DefiController extends AbstractController
{
    #[Route(name: 'app_defi_index', methods: ['GET'])]
    public function index(DefiRepository $defiRepository): Response
    {
        return $this->render('defi/index.html.twig', [
            'defis' => $defiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_defi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $defi = new Defi();
        $form = $this->createForm(DefiType::class, $defi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($defi);
            $entityManager->flush();

            return $this->redirectToRoute('app_defi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('defi/new.html.twig', [
            'defi' => $defi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defi_show', methods: ['GET'])]
    public function show(Defi $defi): Response
    {
        return $this->render('defi/show.html.twig', [
            'defi' => $defi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_defi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Defi $defi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefiType::class, $defi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_defi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('defi/edit.html.twig', [
            'defi' => $defi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_defi_delete', methods: ['POST'])]
    public function delete(Request $request, Defi $defi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($defi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_defi_index', [], Response::HTTP_SEE_OTHER);
    }
}
