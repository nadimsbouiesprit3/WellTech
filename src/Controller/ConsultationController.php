<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConsultationRepository;
use Symfony\Component\HttpFoundation\Request;


class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'index_consultation')]
    public function index(): Response
    {
        $user = $this->getUser();
        $consultations = $user->getConsultations();

        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultations,
        ]);
    }


    #[Route('/add_consultation', name: 'add_consultation')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setPatient($this->getUser()); 
            $consultation->setCreatedAt(new \DateTime()); 
    
            $entityManager->persist($consultation);
            $entityManager->flush();
    
            return $this->redirectToRoute('consultation_index');
        }
    
        return $this->render('consultation/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/consultation/{id}/delete', name: 'consultation_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
    {

        $consultation = $consultationRepository->find($id);
    
        if (!$consultation) {
            throw $this->createNotFoundException('Consultation not found.');
        }
    

        if ($this->getUser() !== $consultation->getPatient()) {
            throw $this->createAccessDeniedException('You are not allowed to delete this consultation.');
        }
    

        if ($this->isCsrfTokenValid('delete' . $consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Consultation deleted successfully.');
        }
    
        return $this->redirectToRoute('index_consultation');
    }
}
