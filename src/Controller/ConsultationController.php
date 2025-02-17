<?php

namespace App\Controller;

use App\Entity\User;
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

            if (!$consultation->getConsultationDate()) {
                throw new \Exception('La date de consultation ne peut pas être vide.');
            }
    
            $entityManager->persist($consultation);
            $entityManager->flush();
    
            return $this->redirectToRoute('consultation_index');
        }
    
        return $this->render('consultation/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/psychiatrist/consultations', name: 'psychiatrist_consultations')]
    public function viewPendingConsultations(EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
    {
        $psychiatrist = $this->getUser();
    
        if (!in_array('ROLE_PSYCHIATRE', $psychiatrist->getRoles())) {
            throw $this->createAccessDeniedException('Access denied.');
        }
    
        $consultations = $consultationRepository->getPendingConsultationsForPsychiatrist($psychiatrist);
    
        return $this->render('consultation/pending_list.html.twig', [
            'consultations' => $consultations,
        ]);
    }
    
    

    #[Route('/consultation/{id}/accept', name: 'consultation_accept')]
    public function acceptConsultation(int $id, EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
    {
        $consultation = $consultationRepository->find($id);
    
        if (!$consultation) {
            throw $this->createNotFoundException('Consultation introuvable.');
        }
    
        if (!$this->isGranted('ROLE_PSYCHIATRE')) {
            throw $this->createAccessDeniedException('Access denied.');
        }
    
        $consultation->setStatut('accepted');
        $entityManager->flush();
    
        $this->addFlash('success', 'Consultation accepted successfully.');
    
        return $this->redirectToRoute('psychiatrist_consultations');
    }

    #[Route('/consultation/{id}/decline', name: 'consultation_decline')]
public function declineConsultation(int $id, EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
{
    $consultation = $consultationRepository->find($id);

    if (!$consultation) {
        throw $this->createNotFoundException('Consultation not found.');
    }

    if (!$this->isGranted('ROLE_PSYCHIATRE')) {
        throw $this->createAccessDeniedException('Access denied.');
    }

    $consultation->setStatut('declined'); 
    $entityManager->flush();

    $this->addFlash('error', 'Consultation declined.');

    return $this->redirectToRoute('psychiatrist_consultations');
}

#[Route('/psychiatrist/consultations/{status}', name: 'psychiatrist_consultations_by_status')]
public function viewConsultationsByStatus(string $status, EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
{
    $psychiatrist = $this->getUser();

    if (!in_array('ROLE_PSYCHIATRE', $psychiatrist->getRoles())) {
        throw $this->createAccessDeniedException('Access denied.');
    }

    if (!in_array($status, ['accepted', 'declined'])) {
        throw $this->createNotFoundException('Invalid consultation status.');
    }

    $consultations = $consultationRepository->getConsultationsByStatus($psychiatrist, $status);

    return $this->render('consultation/status_list.html.twig', [
        'consultations' => $consultations,
        'status' => ucfirst($status),
    ]);
}


    
}
