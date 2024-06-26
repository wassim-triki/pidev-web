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
use App\Enum\TypeReclamationEnum;
use Cloudinary\Cloudinary;

class ReclamationController extends AbstractController
{

    #[Route('/addReclamation', name: 'addReclamation')]
    public function addReclamation(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger,Cloudinary $cloudinary): Response
    {
        $entityManager = $managerRegistry->getManager();

        // Créer une nouvelle réclamation
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

           /** @var UploadedFile $photoFile */
        $photoFile = $form->get('screenShot')->getData();
        if ($photoFile) {
            // Upload to Cloudinary
            $uploadResult = $cloudinary->uploadApi()->upload($photoFile->getPathname());
            $reclamation->setScreenShot($uploadResult['secure_url']);
        }

            $entityManager->persist($reclamation);


            // Créer un nouvel avertissement ou récupérer celui existant
            $avertissement = $entityManager->getRepository(Avertissement::class)->findOneBy(['ReportedUsername' => $reclamation->getReportedUsername()]);


            $avertissement = new Avertissement();
            $avertissement->setReportedUsername($reclamation->getReportedUsername());
            $avertissement->setRaison($reclamation->getTypeReclamation());
            $avertissement->setScreenShot($reclamation->getScreenShot());




            // Ajouter la réclamation à l'avertissement
            $reclamation->setS($avertissement);

            // Persistez l'avertissement et la réclamation
            $entityManager->persist($avertissement);
            $entityManager->flush();
            $this->addFlash(
                'success', // Type de message, peut être 'success', 'warning', 'error', etc.
                'Claim added successfully' // Le message à afficher
            );
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
    public function  editreclamation($id, ManagerRegistry $managerRegistry, Request $req, ReclamationRepository $reclamationrep, SluggerInterface $slugger,Cloudinary $cloudinary): Response
    {

        $em = $managerRegistry->getManager();
        $reclamation = $reclamationrep->find($id);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            $photoFile = $form->get('screenShot')->getData();
            if ($photoFile) {
                // Upload to Cloudinary
                $uploadResult = $cloudinary->uploadApi()->upload($photoFile->getPathname());
                $reclamation->setScreenShot($uploadResult['secure_url']);
            }
            if ($reclamation->getS() && $reclamation->getS()->isConfirmation()) {
                // Si oui, afficher un message et rediriger
                $this->addFlash('error', 'This claim has been confirmed by the administrator and cannot be updated.');
                return $this->redirectToRoute('listreclamation');
            }

            $em->persist($reclamation);

            $em->flush();
            return $this->redirectToRoute('listreclamation');
        }
        return $this->renderForm('front_office/reclamation/ajoutReclamation.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/deletereclamation{id}', name: 'deletereclamation')]

    public function deletereclamation($id, ManagerRegistry $managerRegistry, ReclamationRepository $reclamationrep): Response
    {
        $em = $managerRegistry->getManager();
        $reclamation = $reclamationrep->find($id);

        // Vérifier si la réclamation a été confirmée par l'administrateur
        if ($reclamation->getS() && $reclamation->getS()->isConfirmation()) {
            // Si oui, afficher un message et rediriger
            $this->addFlash('error', 'This claim has been confirmed by the administrator and cannot be deleted.');
            return $this->redirectToRoute('listreclamation');
        }
        $em->remove($reclamation);
        $em->flush();
        $this->addFlash(
            'success', // Type de message, peut être 'success', 'warning', 'error', etc.
            'Claim deleted successfully' // Le message à afficher
        );
        return $this->redirect('listreclamation');
    }
}
