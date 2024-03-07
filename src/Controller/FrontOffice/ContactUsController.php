<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;


class ContactUsController extends AbstractController
{
    #[Route('/contact-us', name: 'contact-us')]
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process form data and send email
            $formData = $form->getData();

            $email = (new Email())
                ->from($formData['email'])
                ->to('your@mailtrap.io')
                ->subject($formData['subject'])
                ->html('
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; background-image: linear-gradient(to right top, #051937, #004d7a, #008793, #00bf72, #a8eb12);">
                <p>message of the day :  </p>
                <br>
                <h5>User Information:</h5>'.
                '<p style="color:#ffff"> name' .$formData['name'] . '</p>' . '<p style="color:#ffff"> Email :' .$formData['email'] . '</p>'  . '<p style="color:#ffff"> message' .$formData['message'] . '🚀</p>');

            $mailer->send($email);

            // Redirect or add any other logic after successful form submission
            $this->addFlash('success', 'Your message has been sent successfully');        }

        return $this->render('front_office/contactUs/contactUs.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
