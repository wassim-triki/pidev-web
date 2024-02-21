<?php

namespace App\Controller\BackOffice;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard()
    {
        // Only allow authenticated users with the ROLE_ADMIN to access this page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('back_office/dashboard/dashboard.html.twig');
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

    #[Route('/delete-user/{id}', name: 'admin_delete_user', methods: ['POST'])]
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
}
