<?php

namespace App\Controller\BackOffice;

use App\Entity\Postcommentaire;
use App\Entity\PostGroup;
use App\Form\FormCommentaireType;
use App\Repository\PostcommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;




class CommentaireAdminController extends AbstractController
{
    
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('front_office/commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/showdbcommentaire', name: 'showdbcommentaire')]
    public function showdbcommentaire(PostcommentaireRepository $PcRepository, Request $request): Response
    {
        $commentaire = $PcRepository->findAll();


        return $this->renderForm('back_office/commentaire/showdbcommentaire.html.twig', [
            'commentaire' => $commentaire
        ]);
    }


    


    #[Route('/deletecommentAdmin/{id}', name: 'deletecommentAdmin')]
    public function deletecommentAdmin($id, PostcommentaireRepository $PcRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $PcRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        $this->addFlash('success', 'Votre poste a été suprimé');
        return $this->redirectToRoute('showdbcommentaire');
    }





   
}
