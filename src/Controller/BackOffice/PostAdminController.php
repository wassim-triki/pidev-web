<?php

namespace App\Controller\BackOffice;

use App\Repository\PostRepository;
use App\Service\JwtTokenService;
use Doctrine\Persistence\ManagerRegistry;
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


    #[Route('/posts', name: 'posts')]
    public function showpostByAdmin(JwtTokenService $postStatisticsService, PostRepository $postRepository): Response
    {
        $post = $postRepository->findAll();
        $statistics = $postStatisticsService->getPostStatistics();
        return $this->render('back_office/dashboard/dashboard.html.twig', [
            'statistics' => $statistics,
            'post' => $post
        ]);
    }


    #[Route('/deletepost/{id}', name: 'deletepost')]
    public function deletepostadmin($id, PostRepository $postRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $postRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        $this->addFlash('echec', 'Post successfully deleted!');
        return $this->redirectToRoute('posts');
    }

    #[Route('/statistics', name: 'post_statistics')]


    #[Route('/a', name: 'a')]
    public function a(): Response
    {
        return $this->render('back_office/base.html.twig');
    }
}
