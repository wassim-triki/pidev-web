<?php

namespace App\Controller;


use App\Service\CloudinaryService;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
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
    public function addpost(ManagerRegistry $managerRegistry, Request $req, CloudinaryService $cloudinaryService): Response
    {
        $em = $managerRegistry->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

        $file = $form->get('album')->getData(); // Ensure 'album' is the correct form field name

        if ($file) {
            $url = $cloudinaryService->upload($file);
            $post->setImageUrl($url); // Assuming your Post entity has this method implemented
        }

            $em->persist($post);
            $em->flush();
        }
        return $this->renderForm('post/addpost.html.twig', [
            'f' => $form
        ]);
    }
}
