<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


// src/Controller/BackOffice/SecurityController.php

    #[Route('/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Check if the user is already logged in and has the ROLE_ADMIN
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the user is authenticated but does not have the ROLE_ADMIN
        if ($this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $error = new CustomUserMessageAuthenticationException('You are NOT and Admin!');
        }

        return $this->render('back_office/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    #[Route('/logout', name: 'admin_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
