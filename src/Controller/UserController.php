<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/settings', name: 'user_settings')]
    public function userSettings(Request $request): Response
    {
        $tab = $request->query->get('tab', 'social');

        return $this->render('user/settings.html.twig', [
            'selectedTab' => $tab,
        ]);
    }


}
