<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountInformationFormType;
use App\Form\ChangePasswordType;
use App\Form\DeleteAccountType;
use App\Form\EmailChangeFormType;
use App\Form\ProfilePictureType;
use App\Repository\UserRepository;
use App\Service\JwtTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends AbstractController
{
    #[Route('/user/{username}', name: 'user_profile')]
    public function userProfile(string $username, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username'=>$username]);

        // Create the profile picture form
        $profilePictureForm = $this->createForm(ProfilePictureType::class);

        $isOwnProfile = $this->getUser() && $this->getUser()->getUsername() === $user->getUsername();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'profilePictureForm' => $profilePictureForm->createView(),
        ]);
    }

    #[Route('/settings', name: 'user_settings')]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)]
    public function userSettings(Request $request,UserPasswordEncoderInterface $passwordEncoder,MailerInterface $mailer,JwtTokenService $jwtTokenService): Response
    {
        $tab = $request->query->get('tab', 'account');

        // Create and handle the form
        $user = $this->getUser();
        $accountForm = $this->createForm(AccountInformationFormType::class, $user);
        $accountForm->handleRequest($request);

        $emailChangeForm = $this->createForm(EmailChangeFormType::class);
        $emailChangeForm->handleRequest($request);

        $deleteAccountForm = $this->createForm(DeleteAccountType::class);
        $deleteAccountForm->handleRequest($request);

        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);



        if ($accountForm->isSubmitted() && $accountForm->isValid()) {
            // Save the updated user information
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('acc_succ', 'Account information updated successfully.');

            return $this->redirectToRoute('user_settings',['tab'=>'account']);
        }

        if ($emailChangeForm->isSubmitted() && $emailChangeForm->isValid()) {
            $oldEmail = $emailChangeForm->get('oldEmail')->getData();
            $newEmail = $emailChangeForm->get('newEmail')->getData();

            // Check if the new email already exists in the database
            $existingUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $newEmail]);
            if ($existingUser) {
                $this->addFlash('em_err', 'This email is already in use.');
                return $this->redirectToRoute('user_settings', ['tab' => 'email']);
            }

            if ($oldEmail === $user->getEmail()) {
                // Generate a verification token with JwtTokenService
                $verificationToken = $jwtTokenService->createToken(['user_id' => $user->getId()], new \DateInterval('PT1H'));

                // Set the new email and verification token
                $user->setEmail($newEmail);
                $user->setIsVerified(false);
                $user->setEmailVerificationToken($verificationToken);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                // Send verification email with JWT token
                $verificationUrl = $this->generateUrl('app_verify_email', ['token' => $verificationToken], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                    ->from('no-reply@al9ani.tn')
                    ->to($newEmail)
                    ->subject('New Email Verification')
                    ->html("Please click on the following link to verify your new email: <a href='$verificationUrl'>$verificationUrl</a>");

                $mailer->send($email);

                $this->addFlash('em_succ', 'A verification link has been sent to your new email address.');
            } else {
                $this->addFlash('em_err', 'Old email address does not match.');
            }

            return $this->redirectToRoute('user_settings', ['tab' => 'email']);
        }



        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $oldPassword = $changePasswordForm->get('oldPassword')->getData();
            $newPassword = $changePasswordForm->get('newPassword')->getData();

            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {

                $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('pass_succ', 'Password changed successfully.');
                return $this->redirectToRoute('user_settings',['tab'=>"password"]);
            } else {
                $this->addFlash('pass_err', 'Old password is incorrect.');
            }
        }

        return $this->render('user/settings.html.twig', [
            'selectedTab' => $tab,
            'accountForm' => $accountForm->createView(),
            'emailChangeForm' => $emailChangeForm->createView(),
            'deleteAccountForm' => $deleteAccountForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }


    #[Route('/user/{username}/update-photo', name: 'user_update_photo', methods: ['POST'])]
    public function updatePhoto(Request $request, string $username, UserRepository $userRepository, SluggerInterface $slugger): Response
    {

        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user || $user !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('user_profile', ['username' => $username]);
        }

        $form = $this->createForm(ProfilePictureType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {

                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move($this->getParameter('uploads_directory'), $newFilename);
                    $user->setPhoto($newFilename);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                    $this->addFlash('success', 'Profile photo updated successfully.');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading photo.');
                }
            }
        }

        return $this->redirectToRoute('user_profile', ['username' => $username]);
    }


    #[Route('/settings/delete-account', name: 'user_delete_account', methods: ['POST'])]
    public function deleteAccount(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder,TokenStorageInterface $tokenStorage): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(DeleteAccountType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $formData = $form->getData();

            if ($user->getEmail() === $formData['email'] && $passwordEncoder->isPasswordValid($user, $formData['password'])) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();

                // Invalidate the session after deleting the user
                $session = $request->getSession();
                if ($session) {
                    $session->invalidate();
                }
                $tokenStorage->setToken();

                $this->addFlash('del_succ', 'Account deleted successfully.');
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('del_err', 'Invalid email or password.');
            }
        }

        return $this->redirectToRoute('user_settings', ['tab' => 'delete']);
    }












}
