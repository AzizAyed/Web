<?php

namespace App\Controller;

use App\Entity\Station;
use App\Repository\StationRepository;
use App\Form\StationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class StationController extends AbstractController
{
    
    #[Route('/nosstations', name: 'nosstations_route')]
    public function nosStation(StationRepository $StationRepository): Response
    {
        return $this->render('nosStations.html.twig', [
            'Stations' => $StationRepository->findAll(),
        ]);
    }
    
    #[Route('/Station/tous', name: 'listes_Stations')]
    public function listAuthor(StationRepository $StationRepository): Response
    {
        return $this->render('tousStation.html.twig', [
            'Stations' => $StationRepository->findAll(),
        ]);
    }

    #[Route('/Station/ajouter', name: 'ajouter_Station')]
    public function ajouterStation(Request $request): Response
    {
        $Station = new Station();
        $form = $this->createForm(StationType::class, $Station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Station);
            $entityManager->flush();

            return $this->redirectToRoute('listes_Stations');
        }

        return $this->render('ajouterStation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/Station/modifier/{idStation}', name: 'modifierStation')]   
public function ModifierStation(ManagerRegistry $manager, Request $request, $idStation, StationRepository $StationRepository): Response
{   
    $em = $manager->getManager();
    $Station = $StationRepository->find($idStation);

    // Create a new Station object
    $newStation = new Station();
    $form = $this->createForm(StationType::class, $newStation);

    // Set the initial values of the form fields based on the $Station entity
    $form->get('nom')->setData($Station->getNom());
    $form->get('adresse')->setData($Station->getAdresse());
    $form->get('lat')->setData($Station->getLat());
    $form->get('longi')->setData($Station->getLongi());

    // Handle the form request
    $form->handleRequest($request);

    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Update the existing $Station entity with data from the newStation object
        $Station->setNom($newStation->getNom());
        $Station->setAdresse($newStation->getAdresse());
        $Station->setLat($newStation->getLat());
        $Station->setLongi($newStation->getLongi());

        // Persist and flush the updated entity
        $em->persist($Station);
        $em->flush();

        // Redirect to the list of Stations
        return $this->redirectToRoute('listes_Stations');
    }

    // Render the form
    return $this->renderForm('ModifierStation.html.twig', [
        'Station' => $Station,
        'form' => $form,
    ]);
}

#[Route('/Station/supprimer/{idStation}', name: 'supprimerStation')]
public function SupprimerStation(Request $request, $idStation, ManagerRegistry $manager, StationRepository $StationRepository): Response
{
    $em = $manager->getManager();
    $Station = $StationRepository->find($idStation);
        $em->remove($Station);
        $em->flush();
    return $this->redirectToRoute('listes_Stations');
}
}
