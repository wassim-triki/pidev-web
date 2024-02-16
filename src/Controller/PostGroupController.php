<?php

namespace App\Controller;

use App\Entity\PostGroup;
use App\Form\PostGroupType;
use App\Repository\PostGroupRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostGroupController extends AbstractController
{
    #[Route('/post/group', name: 'app_post_group')]
    public function index(): Response
    {
        return $this->render('post_group/index.html.twig', [
            'controller_name' => 'PostGroupController',
        ]);
    }

    #[Route('/addpostgroup', name: 'addpostgroup')]
    public function addpostgroup(ManagerRegistry $managerRegistry, Request $req, PostGroupRepository $postRepository): Response
    {
        $em = $managerRegistry->getManager();
        $post = new PostGroup();
        $form = $this->createForm(PostGroupType::class, $post);
        $form->handleRequest($req);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new \DateTime();
            $post->setDate($currentDate);
            $em->persist($post);
            $em->flush();
        }
    
        $showpost = $postRepository->findAll();
    
        return $this->renderForm('post_group/group.html.twig', [
            'f' => $form,
            'showpost' => $showpost
        ]);
    }

    #[Route('/editpost/{id}', name: 'editpost')]
    public function editpost($id, PostGroupRepository $postRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        $dataid = $postRepository->find($id);

        $form = $this->createForm(PostGroupType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('group');
        }

        return $this->renderForm('post_group/group.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/deletepost/{id}', name: 'deletepost')]
    public function deletepost($id, PostGroupRepository $postRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $postRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('post_group/group.html.twig');
    }
    

    
}
