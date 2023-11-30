<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class OffreController extends AbstractController
{
    #[Route('/home', name: 'home_route')]
    public function exampleAction(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/nosoffres', name: 'nosoffres_route')]
    public function nosOffre(OffreRepository $OffreRepository): Response
    {
        return $this->render('nosOffres.html.twig', [
            'Offres' => $OffreRepository->findAll(),
        ]);
    }
    
    #[Route('/offre/tous', name: 'listes_Offres')]
    public function listAll(Request $request, OffreRepository $offreRepository): Response
    {
        // Récupérez le paramètre de recherche depuis la requête
        $searchTerm = $request->query->get('searchTerm');
    
        // Récupérez les paramètres de tri depuis la requête
        $sortBy = $request->query->get('sortBy', 'nom'); // default to sorting by name
        $order = $request->query->get('order', 'asc'); // default to ascending order
    
        // Utilisez la méthode de recherche et tri du repository pour obtenir les résultats
        $offres = $offreRepository->searchAndSort($searchTerm, $sortBy, $order);
    
        // Renvoyez les résultats au template
        return $this->render('tousOffre.html.twig', [
            'Offres' => $offres,
            'searchTerm' => $searchTerm,
            'sortBy' => $sortBy,
            'order' => $order,
        ]);
    }    
    

    #[Route('/Offre/ajouter', name: 'ajouter_offre')]
    public function ajouterOffre(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('listes_Offres');
        }

        return $this->render('ajouterOffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/Offre/modifier/{idOffre}', name: 'modifierOffre')]   
    public function ModifierOffre(ManagerRegistry $manager, Request $request, $idOffre, OffreRepository $OffreRepository): Response
    {   
        $em = $manager->getManager();
        $Offre = $OffreRepository->find($idOffre);
    
        // Create a new Offre object
        $newOffre = new Offre();
        $form = $this->createForm(OffreType::class, $newOffre);
    
        // Set the initial values of the form fields based on the $Offre entity
        $form->get('nom')->setData($Offre->getNom());
        $form->get('prix')->setData($Offre->getPrix());
        $form->get('duree')->setData($Offre->getDuree());
    
        // Handle the form request
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the existing $Offre entity with data from the newOffre object
            $Offre->setNom($newOffre->getNom());
            $Offre->setPrix($newOffre->getPrix());
            $Offre->setDuree($newOffre->getDuree());
    
            // Persist and flush the updated entity
            $em->persist($Offre);
            $em->flush();
    
            // Redirect to the list of Offres
            return $this->redirectToRoute('listes_Offres');
        }
    
        // Render the form
        return $this->renderForm('ModifierOffre.html.twig', [
            'Offre' => $Offre,
            'form' => $form,
        ]);
    }
    
    


#[Route('/Offre/supprimer/{idOffre}', name: 'supprimerOffre')]
public function SupprimerOffre(Request $request, $idOffre, ManagerRegistry $manager, OffreRepository $OffreRepository): Response
{
    $em = $manager->getManager();
    $Offre = $OffreRepository->find($idOffre);
        $em->remove($Offre);
        $em->flush();
    return $this->redirectToRoute('listes_Offres');
}
}
