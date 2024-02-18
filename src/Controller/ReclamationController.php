<?php

namespace App\Controller;

use App\Entity\Avertissement;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/addReclamation', name: 'addReclamation')]
    public function addReclamation(ManagerRegistry $managerRegistry,Request $req):Response
    {
        $entityManager = $managerRegistry->getManager();

    // Créer une nouvelle réclamation
    $reclamation = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les données du formulaire
        $ReportedUsername = $reclamation->getReportedUsername();
        $raison = $reclamation->getTypeReclamation();
        $screenShot = $reclamation->getScreenShot();

        // Créer un nouvel avertissement ou récupérer celui existant
        $avertissement = $entityManager->getRepository(Avertissement::class)->findOneBy(['ReportedUsername' => $ReportedUsername]);

        
            $avertissement = new Avertissement();
            $avertissement->setReportedUsername($ReportedUsername);
            $avertissement->setRaison($raison);
            $avertissement->setScreenShot($screenShot);
            $avertissement->setNombreReclamation(1);
       
        

        // Ajouter la réclamation à l'avertissement
        $reclamation->setS($avertissement);

        // Persistez l'avertissement et la réclamation
        $entityManager->persist($avertissement);
        $entityManager->persist($reclamation);
        $entityManager->flush();
       return $this->redirect('listreclamation');
         
        }
       return $this->renderForm('reclamation/ajoutReclamation.html.twig', [
        'f' => $form,
    ]);
    }
    #[Route('/listreclamation', name: 'listreclamation')]
    public function listReclamation(ReclamationRepository $reclamationRepository): Response
    {
        $list = $reclamationRepository->findAll();

        return $this->render('reclamation/listereclamation.html.twig', [
            'list' => $list,
        ]);
    }
 

    #[Route('/editreclamation{id}', name: 'editreclamation')]
    public function  editreclamation ($id,ManagerRegistry $managerRegistry,Request $req,ReclamationRepository $reclamationrep): Response
    {
        $em=$managerRegistry->getManager();
        $reclamation=$reclamationrep->find($id);
        $form=$this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($reclamation);
        $em->flush();
       return $this->redirect('listreclamation');
        }
        return $this->renderForm('reclamation/ajoutReclamation.html.twig', [
            'f' => $form,
        ]);
        
    }
    #[Route('/deletereclamation{id}', name: 'deletereclamation')]

    public function deletereclamation($id,ManagerRegistry $managerRegistry,ReclamationRepository $reclamationrep): Response
    {
        $em=$managerRegistry->getManager();
        $reclamation=$reclamationrep->find($id);
        $em->remove($reclamation);
        $em->flush();
       return $this->redirect('listreclamation');
    }

     


   

}
