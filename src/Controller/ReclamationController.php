<?php

namespace App\Controller;

use App\Entity\Avertissement;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
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
            $screenShot = $form->get('screenShot')->getData();

            // Vérifier si un fichier a été téléchargé
            if ($screenShot) {
                // Générez un nom de fichier unique
                $fileName = uniqid().'.'.$screenShot->guessExtension();

                // Déplacez le fichier dans le répertoire où vous souhaitez enregistrer les screenshots
                $screenShot->move(
                    $this->getParameter('screenshot_directory'),
                    $fileName
                );

                // Stockez le nom du fichier dans votre entité Reclamation
                $reclamation->setScreenshot($fileName);}
           
            $emailReportedUser = $reclamation->getEmailReportedUser();

            $avertissement = $entityManager->getRepository(Avertissement::class)->findOneBy(['email' => $emailReportedUser]);

            if (!$avertissement) {
               
                $avertissement = new Avertissement();
                $avertissement->setEmail($emailReportedUser);
                $avertissement->setNombreReclamation(1); 
            } else {
               
                $avertissement->setNombreReclamation($avertissement->getNombreReclamation() + 1);
            }

           
            $reclamation->setS($avertissement);

           
            $entityManager->persist($reclamation);
            $entityManager->persist($avertissement);
            $entityManager->flush();

         
        }
       return $this->renderForm('reclamation/ajoutReclamation.html.twig', [
        'f' => $form,
    ]);
    }

   

}
