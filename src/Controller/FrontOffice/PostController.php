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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }


    #[Route('/', name: 'showpost')]
    public function showpost(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $post = $postRepository->findAll();
        $post = $paginator->paginate(
            $post, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('front_office/post/showtest.html.twig', [
            'post' => $post
        ]);
    }



    #[Route('/search', name: 'search')]
    public function search(Request $request, PostRepository $postRepository): Response
    {
        $query = $request->query->get('query');
        $date = $request->query->get('date');

        $qb = $postRepository->createQueryBuilder('e');

        if ($query) {
            $qb->where('e.titre LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($date) {
            $qb->andWhere('e.date = :date')
                ->setParameter('date', new \DateTime($date));
        }

        $results = $qb->getQuery()->getResult();

        // Transform results to array to prepare for JSON response
        $formattedResults = [];
        foreach ($results as $result) {
            // Customize the fields you want to include in the response
            $formattedResults[] = [
                'titre' => $result->getTitre(),
                'description' => $result->getDescription(),
                'date' => $result->getDate(),
                'type' => $result->getType(),
                'imageUrl' => $result->getImageUrl(),
                'place' => $result->getPlace(),

                // Add more fields as needed
            ];
        }

        // Return JSON response
        return new JsonResponse($formattedResults);
    }



    #[Route('/addpost', name: 'addpost')]
    public function addpost(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger, HubInterface $hub): Response
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
            return $this->redirectToRoute('addpost');
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

    #[Route('/user/{username}', name: 'showpostid')]
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
