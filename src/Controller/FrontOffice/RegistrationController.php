<?php

// src/Controller/RegistrationController.php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

// Add this line
// Optional, if you want to catch file-specific exceptions

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger): Response // Add SluggerInterface
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $verificationToken = bin2hex(random_bytes(32));
            $user->setEmailVerificationToken($verificationToken);

            $verificationUrl = $this->generateUrl('app_verify_email', ['token' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('no-reply@al9ani.tn')
                ->to($user->getEmail())
                ->subject('Email Verification')
                ->html("Please click on the following link to verify your email: <a href='$verificationUrl'>$verificationUrl</a>");

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // Log or handle the error as needed
                $this->addFlash('error', 'Failed to send email: ' . $e->getMessage());
            }


            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData(); // Assuming 'photo' is the field name in your form

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Use the slugger to create a safe filename
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

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

            $entityManager->persist($user);
            $entityManager->flush();

            // After successful registration
            $this->addFlash('registered_email', "Verification link sent to ".$user->getEmail());
            // Redirect to some route after the registration
//            return $this->redirectToRoute('app_login');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('front_office/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    // src/Controller/RegistrationController.php

    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        $user = $userRepository->findOneBy(['emailVerificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('This verification token is invalid.');
        }

        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Your email has been verified.');

        return $this->redirectToRoute('app_login');
    }

}
