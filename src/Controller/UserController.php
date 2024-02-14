<?php

namespace App\Controller;

use App\Form\AccountInformationFormType;
use App\Form\EmailChangeFormType;
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
        $tab = $request->query->get('tab', 'account');

        // Create and handle the form
        $user = $this->getUser();
        $accountForm = $this->createForm(AccountInformationFormType::class, $user);
        $accountForm->handleRequest($request);

        $emailChangeForm = $this->createForm(EmailChangeFormType::class);
        $emailChangeForm->handleRequest($request);



        if ($accountForm->isSubmitted() && $accountForm->isValid()) {
            // Save the updated user information
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Account information updated successfully.');

            return $this->redirectToRoute('user_settings');
        }

        if ($emailChangeForm->isSubmitted() && $emailChangeForm->isValid()) {
            $oldEmail = $emailChangeForm->get('oldEmail')->getData();
            $newEmail = $emailChangeForm->get('newEmail')->getData();

            if ($oldEmail === $user->getEmail()) {
                $user->setEmail($newEmail);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                $this->addFlash('success', 'Email address updated successfully.');
            } else {
                $this->addFlash('matchError', 'Old email address does not match.');
            }

            return $this->redirectToRoute('user_settings', ['tab' => 'email']);
        }

        return $this->render('user/settings.html.twig', [
            'selectedTab' => $tab,
            'accountForm' => $accountForm->createView(),
            'emailChangeForm' => $emailChangeForm->createView(),
        ]);
    }


}
