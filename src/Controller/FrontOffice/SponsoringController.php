<?php

namespace App\Controller\FrontOffice;

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

   

  

    #[Route('/showsponsor', name: 'showsponsor')]
    public function showsponsor(SponsoringRepository $sponsorRepository, Request $request): Response
    {
        $sponsor = $sponsorRepository->findAll();


        return $this->renderForm('front_office/sponsoring/showsponsor.html.twig', [

            'sponsor' => $sponsor
        ]);
    }


    
   

}
