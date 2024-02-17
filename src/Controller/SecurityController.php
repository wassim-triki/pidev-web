<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Retrieve registered email from flash message if available
        $flashBag = $this->get('session')->getFlashBag();
        $registeredEmail = $flashBag->get('registered_email');
        if (!empty($registeredEmail)) {
            $lastUsername = $registeredEmail[0];
            // Clear the flash message to prevent it from appearing on subsequent requests
            $flashBag->clear('registered_email');
        }
        return $this->renderForm('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    // src/Controller/SecurityController.php

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email'=>$email]);

            if ($user) {
                // Generate a reset token
                $resetToken = bin2hex(random_bytes(32));
                $user->setResetToken($resetToken);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Send email with reset token
                $resetUrl = $this->generateUrl('app_reset_password', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                    ->from('no-reply@al9ani.tn')
                    ->to($user->getEmail())
                    ->subject('Password Reset Request')
                    ->html("Please click on the following link to reset your password: <a href='$resetUrl'>$resetUrl</a>");

                $mailer->send($email);

                $this->addFlash('success', 'A password reset link has been sent to your email.');
//                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', 'No account found with this email.');
            }
        }

        return $this->render('security/forgot_password.html.twig');
    }


    // src/Controller/SecurityController.php

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $userRepository->findOneBy(['resetToken'=>$token]);


        if (!$user) {
            $this->addFlash('error', 'Invalid or expired password reset token.');
            return $this->redirectToRoute('app_forgot_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();

            // Encode and set the new password
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            // Clear the reset token
            $user->setResetToken(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your password has been reset successfully.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }


}
