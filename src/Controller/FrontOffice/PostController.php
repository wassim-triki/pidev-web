<?php

namespace App\Controller\FrontOffice;


use App\Entity\Post;
use App\Enum\PostTypeEnum;
use App\Form\PostType;
use App\Form\ProfilePictureType;
use App\Form\SearchPostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/search', name: 'post_search')]
    public function search(Request $request, PostRepository $postRepository)
    {
        $form = $this->createForm(SearchPostType::class);
        $form->handleRequest($request);

        $posts = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $posts = $postRepository->findByTitle($data['search']);
        }

        return $this->render('front_office/post/showtest2.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    #[Route('/showpost', name: 'showpost')]
    public function showpost(Request $request, PostRepository $postRepository): Response
    {
        $form = $this->createForm(SearchPostType::class);
        $form->handleRequest($request);
        $posts = [];
        $posts = $postRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $posts = $postRepository->findByTitle($data['search']);
        }
        return $this->render('front_office/post/showtest.html.twig', [
            'form' => $form->createView(),
            'post' => $posts
        ]);
    }



    #[Route('/addpost', name: 'addpost')]
    public function addpost(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $em = $managerRegistry->getManager();
        $user = $this->getUser();
        $post = new Post();
        $post->setDate(new \DateTime());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);

        $typeChoices = [
            'Lost' => PostTypeEnum::LOST->value,
            'Found' => PostTypeEnum::FOUND->value,
        ];

        if ($form->isSubmitted()) {
            $photoFile = $form->get('imageUrl')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $post->setImageUrl($newFilename);
                } catch (FileException $e) {
                    // Handle error
                }
            }
            $post->setUser($user);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post successfully added!');
            return $this->redirectToRoute('addpost'); // Redirect to a route after successful submission

        }

        return $this->renderForm('front_office/post/addpost2.html.twig', [
            'f' => $form,
            'typeChoices' => $typeChoices,
        ]);
    }


    #[Route('/editpost/{id}', name: 'editpost')]
    public function editpost($id, PostRepository $postRepository, Request $req, ManagerRegistry $managerRegistry): Response
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

        return $this->renderForm('front_office/post/editpost.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/deletepost/{id}', name: 'deletepost')]
    public function deletepost($id, PostRepository $postRepository, ManagerRegistry $managerRegistry): Response
    {
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
        return $this->render('test.html.twig', []);
    }

    #[Route('/user/{username}/posts', name: 'showpostid')]
    public function showpostid($username, PostRepository $postRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $username]);
        $listpost = $postRepository->findByUser($user);

        // Create the profile picture form
        $profilePictureForm = $this->createForm(ProfilePictureType::class);

        $isOwnProfile = $this->getUser() && $this->getUser()->getUsername() === $user->getUsername();
        return $this->render('front_office/post/sowpostown.html.twig', [
            'a' => $listpost,
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'profilePictureForm' => $profilePictureForm->createView(),
        ]);
    }
}
