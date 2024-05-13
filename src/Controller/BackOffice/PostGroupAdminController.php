<?php

namespace App\Controller\BackOffice;

use App\Entity\PostGroup;
use App\Entity\Sponsoring;
use App\Form\PostGroupType;
use App\Repository\PostGroupRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostGroupAdminController extends AbstractController
{


  



    #[Route('/showdbpostgroup', name: 'showdbpostgroup')]
    public function showdbpostgroup(PostGroupRepository $PostGroupRepository, Request $request): Response
    {
        $post = $PostGroupRepository->findAll();


        return $this->renderForm('back_office/post_group/showdbpostgroup.html.twig', [
            'post' => $post
        ]);
    }


  
    #[Route('/deletepostAdmin/{id}', name: 'deletepostAdmin')]
    public function deletepostAdmin($id, PostGroupRepository $PostGroupRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $PostGroupRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        $this->addFlash('success', 'Votre poste a été suprimé');
        return $this->redirectToRoute('showdbpostgroup');
    }
}
