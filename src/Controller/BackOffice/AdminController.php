<?php

namespace App\Controller\BackOffice;

use App\Entity\Market;
use App\Entity\Voucher;
use App\Entity\User;
use App\Entity\VoucherCategory;
use App\Form\AdminEditProfileFormType;
use App\Form\AdminUserCreationFormType;
use App\Form\RegistrationFormType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\JwtTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(JwtTokenService $postStatisticsService, PostRepository $postRepository)
    {
        // Only allow authenticated users with the ROLE_ADMIN to access this page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = $postRepository->findAll();
        $postStatistics = $postStatisticsService->getPostStatistics();
        $sponsorStatistics = $postStatisticsService->getSponsorStatistics();
        return $this->render('back_office/dashboard/dashboard.html.twig', [
            'postStatistics' => $postStatistics,
            'post' => $post,
            'sponsorStatistics' => $sponsorStatistics
        ]);
    }
    #[Route('/dash', name: 'admin_dashboard')]
    public function dashboardYassine()
    {
        return $this->render('backOffice/Dashboard/dashboard.html.twig');
    }

    #[Route('/users', name: 'admin_users')]
    public function users(UserRepository $userRepository)
    {
        // Only allow authenticated users with the ROLE_ADMIN to access this page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Fetch all users from the database
        $users = $userRepository->findAll();

        // Pass the users to the Twig template
        return $this->render('back_office/users/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('admin_users');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/users/{id}/toggle', name: 'admin_toggle_user')]
    public function toggleUser(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('admin_users');
        }

        $user->setIsEnabled(!$user->isEnabled());
        $entityManager->flush();

        $this->addFlash('success', 'User status updated successfully.');

        return $this->redirectToRoute('admin_users');
    }


    #[Route('/users/create', name: 'admin_create_user')]
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(AdminUserCreationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData(); // Assuming 'photo' is the field name in your form

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Use the slugger to create a safe filename
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $user->setPhoto($newFilename); // Assuming you store just the filename; adjust if storing paths
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            // Encode the password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('back_office/users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/profile/edit', name: 'admin_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $form = $this->createForm(AdminEditProfileFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }
            }

            //            if ($form->get('password')->getData()) {
            //                $user->setPassword(
            //                    $passwordEncoder->encodePassword(
            //                        $user,
            //                        $form->get('password')->getData()
            //                    )
            //                );
            //            }

            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('back_office/users/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/dash/voucher-list', name: 'admin-voucher-list')]
    public function voucherList(PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch voucher details from the database
        $vouchers = $this->managerRegistry->getRepository(Voucher::class)->findAll();
        $pagination = $paginator->paginate(
            $vouchers,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('backOffice/Dashboard/crm-voucher-list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/dash/market-list', name: 'admin-market-list')]
    public function marketList(PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch voucher details from the database
        $market = $this->managerRegistry->getRepository(Market::class)->findAll();
        $pagination = $paginator->paginate(
            $market,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('backOffice/Dashboard/crm-market-list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/dash/category-list', name: 'admin-category-list')]
    public function categoryList(PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch voucher details from the database
        $category = $this->managerRegistry->getRepository(VoucherCategory::class)->findAll();
        $pagination = $paginator->paginate(
            $category,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('backOffice/Dashboard/crm-category-list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
