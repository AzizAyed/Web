<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Service\ExcelExportService;
use mofodojodino\ProfanityFilter\Check;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/excel', name: 'app_excel_reclam', methods: ['GET'])]
    public function chicha(ReclamationRepository $reclamationRepository, ExcelExportService $excelExportService): Response
    {
         // Récupérez les stations depuis le repository
         $reclamations = $reclamationRepository->findAll();

        // Exportez les stations vers un fichier Excel
        $excelExportService->exportReclamationsToExcel($reclamations);

        // Utilisez le service Symfony\Component\HttpFoundation\BinaryFileResponse pour renvoyer le fichier Excel au client
       
        $response = new BinaryFileResponse('export_reclamations.xlsx');
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'export_reclamations.xlsx');

        return $response;
        
        
    }

    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

private function checkBadWords($word):bool
{
    $check = new Check('C:\Users\aweld\Downloads\Exam\Exam\src\Controller\BadWords.php');
    $hasProfanity = $check->hasProfanity($word);
    return $hasProfanity;
}

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $reclamation = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($this->checkBadWords($reclamation->getContenu())) {
        $this->addFlash('error', 'Veuiller verifier votre langage ');
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($reclamation);
        $entityManager->flush();

        // Display success message in popup
        $this->addFlash('success', 'Votre reclamation a été envoyée avec succès');

        // Return previous route instead of redirecting
        return $this->redirectToRoute('app_reclamation_new');
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
