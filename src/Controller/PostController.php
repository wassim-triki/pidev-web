<?php

namespace App\Controller;


use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/showpost', name: 'showpost')]
    public function showbook(PostRepository $postRepository): Response
    {
        $post = $postRepository->findAll();
        return $this->render('post/showpost.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/addpost', name: 'addpost')]
    public function addpost(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($post);
            $em->flush();
        }
        return $this->renderForm('post/addpost.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/editpost/{id}', name: 'editpost')]
    public function editcar($id, PostRepository $postRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showpost');
        }

        return $this->renderForm('post/editpost.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/deletepost/{id}', name: 'deletepost')]
    public function deleteroom($id,PostRepository $postRepository,ManagerRegistry $managerRegistry): Response {
        $em = $managerRegistry->getManager();
        $dataid = $postRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showpost');
    }
}
