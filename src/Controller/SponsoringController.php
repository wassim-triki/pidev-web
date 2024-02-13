<?php

namespace App\Controller;

use App\Entity\Sponsoring;
use App\Form\FormSponsoringType;
use App\Repository\SponsoringRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function addformauthor(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $sponsoring = new Sponsoring();
        $form = $this->createForm(FormSponsoringType::class, $sponsoring);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

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

        $form = $this->createForm(FormSponsoringType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $name = $form->get('name')->getData();
            dump($form->getData()) . die();
            $sponsors = $sponsorRepository->searchauthor($name);

            return $this->render('sponsoring/showdbsponsoring.html.twig', array('sponsor' => $sponsors,  'f' => $form->createView()));
        }
        return $this->renderForm('sponsoring/showdbsponsoring.html.twig', [
            'f' => $form,
            'sponsor' => $sponsor
        ]);
    }

    #[Route('/showsponsor', name: 'showsponsor')]
    public function showsponsor(SponsoringRepository $sponsorRepository, Request $request): Response
    {
        $sponsor = $sponsorRepository->findAll();

        $form = $this->createForm(FormSponsoringType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $name = $form->get('name')->getData();
            dump($form->getData()) . die();
            $sponsors = $sponsorRepository->searchauthor($name);

            return $this->render('sponsoring/showsponsor.html.twig', array('sponsor' => $sponsors,  'f' => $form->createView()));
        }
        return $this->renderForm('sponsoring/showsponsor.html.twig', [
            'f' => $form,
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
    public function deletesponsor($id, SponsoringRepository $authorRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $authorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showdbsponsoring');
    }
}
