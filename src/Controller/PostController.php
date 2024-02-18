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
    public function showpost(PostRepository $postRepository): Response
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
        $post->setDate(new \DateTime());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($post);
            $em->flush();
            dump($post);
            $this->addFlash('success', 'Post successfully added!');

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
            $this->addFlash('edit', 'Post successfully edited!');
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
        $this->addFlash('echec', 'Post successfully deleted!');
        return $this->redirectToRoute('showpost');
    }

    #[Route('/go', name: 'go')]
    public function go(PostRepository $postRepository): Response
    {
        return $this->render('test.html.twig', [
        ]);
    }

    #[Route('/showpostid/{user}', name: 'showpostid')]
    public function showpostid($user, PostRepository $postRepository): Response
    {
        $listpost = $postRepository->showpostid($user);
        // var_dump($listbook) . die();
        return $this->render('post/showpost.html.twig', [
            'a' => $listpost
        ]);
    }
}
