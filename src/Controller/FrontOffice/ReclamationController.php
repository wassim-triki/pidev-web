<?php

namespace App\Controller\FrontOffice;

use App\Entity\Avertissement;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReclamationController extends AbstractController
{
 
    #[Route('/addReclamation', name: 'addReclamation')]
    public function addReclamation(ManagerRegistry $managerRegistry,Request $req, SluggerInterface $slugger):Response
    {
        $entityManager = $managerRegistry->getManager();

    // Créer une nouvelle réclamation
    $reclamation = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
         /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
         $photoFile = $form->get('screenShot')->getData(); // Assuming 'photo' is the field name in your form

         if ($photoFile) {
             $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
             // Use the slugger to create a safe filename
             $safeFilename = $slugger->slug($originalFilename);
             $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

             try {
                 $photoFile->move(
                     $this->getParameter('screenshot_directory'),
                     $newFilename
                 );
                 $reclamation->setScreenShot($newFilename); // Assuming you store just the filename; adjust if storing paths
             } catch (FileException $e) {
                 // ... handle exception if something happens during file upload
             }
         }
        $entityManager->persist($reclamation);
 

        // Créer un nouvel avertissement ou récupérer celui existant
        $avertissement = $entityManager->getRepository(Avertissement::class)->findOneBy(['ReportedUsername' => $reclamation->getReportedUsername()]);

        
            $avertissement = new Avertissement();
            $avertissement->setReportedUsername($reclamation->getReportedUsername());
            $avertissement->setRaison($reclamation->getTypeReclamation());
            $avertissement->setScreenShot($reclamation->getScreenShot());
            $avertissement->setNombreReclamation(1);
       
        

        // Ajouter la réclamation à l'avertissement
        $reclamation->setS($avertissement);

        // Persistez l'avertissement et la réclamation
        $entityManager->persist($avertissement);
        $entityManager->flush();
       return $this->redirect('listreclamation');
         
        }
       return $this->renderForm('front_office/reclamation/ajoutReclamation.html.twig', [
        'f' => $form,
    ]);
    }
    #[Route('/listreclamation', name: 'listreclamation')]
    public function listReclamation(ReclamationRepository $reclamationRepository): Response
    {
        $list = $reclamationRepository->findAll();

        return $this->render('front_office/reclamation/listereclamation.html.twig', [
            'list' => $list,
        ]);
    }
 

    #[Route('/editreclamation{id}', name: 'editreclamation')]
    public function  editreclamation ($id,ManagerRegistry $managerRegistry,Request $req,ReclamationRepository $reclamationrep, SluggerInterface $slugger): Response
    {
        $em=$managerRegistry->getManager();
        $reclamation=$reclamationrep->find($id);
        $form=$this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
         $photoFile = $form->get('screenShot')->getData(); // Assuming 'photo' is the field name in your form

         if ($photoFile) {
             $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
             // Use the slugger to create a safe filename
             $safeFilename = $slugger->slug($originalFilename);
             $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

             try {
                 $photoFile->move(
                     $this->getParameter('screenshot_directory'),
                     $newFilename
                 );
                 $reclamation->setScreenShot($newFilename); // Assuming you store just the filename; adjust if storing paths
             } catch (FileException $e) {
                 // ... handle exception if something happens during file upload
             }
         }
           
        $em->persist($reclamation);
        // var_dump($reclamation->getScreenShot());
        // die();
        $em->flush();
       return $this->redirect('listreclamation');
        }
        return $this->renderForm('front_office/reclamation/ajoutReclamation.html.twig', [
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
