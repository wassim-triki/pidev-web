<?php

namespace App\Controller\BackOffice;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostAdminController extends AbstractController
{
    #[Route('/post/admin', name: 'app_post_admin')]
    public function index(): Response
    {
        return $this->render('post_admin/index.html.twig', [
            'controller_name' => 'PostAdminController',
        ]);
    }

    #[Route('/showpostByAdmin', name: 'showpostByAdmin')]
    public function showpostByAdmin(PostRepository $postRepository): Response
    {
        $post = $postRepository->findAll();
        return $this->render('back_office/dashboard/dashboard.html.twig', [
            'post' => $post
        ]);
    }
}
