<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{username}', name: 'user_profile')]
    public function userProfile(string $username, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username'=>$username]);

        $isOwnProfile = $this->getUser() && $this->getUser()->getUsername() === $user->getUsername();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
        ]);
    }
}
