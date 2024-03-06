<?php

namespace App\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $post = $postRepository->findAll();
        $post = $paginator->paginate(
            $post, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('front_office/home/index.html.twig', [
            'post' => $post,
        ]);
    }
}
