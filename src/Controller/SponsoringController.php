<?php

namespace App\Controller;

use App\Entity\Sponsoring;
use App\Form\FormSponsoringType;
use App\Repository\SponsoringRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SponsoringController extends AbstractController
{
    #[Route('/sponsoring', name: 'app_sponsoring')]
    public function index(): Response
    {
        return $this->render('sponsoring/index.html.twig', [
            'controller_name' => 'SponsoringController',
        ]);
    }

    #[Route('/addform', name: 'addform')]
    public function addformauthor(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        $em = $managerRegistry->getManager();
        $sponsoring = new Sponsoring();
        $form = $this->createForm(FormSponsoringType::class, $sponsoring);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('image')->getData(); // Assuming 'photo' is the field name in your form

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
                    $sponsoring->setImage($newFilename); // Assuming you store just the filename; adjust if storing paths
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $em->persist($sponsoring);
            $em->flush();
            return $this->redirectToRoute('showdbsponsoring');
        }
        return $this->renderForm('sponsoring/addform.html.twig', [
            'f' => $form
        ]);
    }
    
    #[Route('/showdbsponsoring', name: 'showdbsponsoring')]
    public function showdbsponsoring(SponsoringRepository $sponsorRepository, Request $request): Response
    {
        $sponsor = $sponsorRepository->findAll();


        return $this->renderForm('sponsoring/showdbsponsoring.html.twig', [
            'sponsor' => $sponsor
        ]);
    }

    #[Route('/showsponsor', name: 'showsponsor')]
    public function showsponsor(SponsoringRepository $sponsorRepository, Request $request): Response
    {
        $sponsor = $sponsorRepository->findAll();

        
        return $this->renderForm('sponsoring/showsponsor.html.twig', [
         
            'sponsor' => $sponsor
        ]);
    }


    #[Route('/editsponsor/{id}', name: 'editsponsor')]
    public function editsponsor($id, SponsoringRepository $sponsorRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        $dataid = $sponsorRepository->find($id);

        $form = $this->createForm(FormSponsoringType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showdbsponsoring');
        }

        return $this->renderForm('sponsoring/editsponsor.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/deletesponsor/{id}', name: 'deletesponsor')]
    public function deletesponsor($id, SponsoringRepository $sponsorRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $sponsorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showdbsponsoring');
    }
}
