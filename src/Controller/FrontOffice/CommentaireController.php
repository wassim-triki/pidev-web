<?php

namespace App\Controller\FrontOffice;

use App\Entity\Postcommentaire;
use App\Entity\PostGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('front_office/commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/postgroup/{id}/commentaire/add', name: 'commentaire_add', methods:"POST")]
    public function addCommentaire(Request $request, PostGroup $postGroup): Response
    {
        $contenu = $request->request->get('contenu');

        $commentaire = new Postcommentaire();
        $commentaire->setCommentaire($contenu);
        $commentaire->setPostGroup($postGroup);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('postgroup_show', ['id' => $postGroup->getId()]);
        
    }

    
}
