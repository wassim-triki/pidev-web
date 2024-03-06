<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Market;
use App\Entity\Voucher;

class HomeController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        $user = $this->getUser();
        $voucher = null;
        if($user) {
            $voucher = $this->managerRegistry->getRepository(Voucher::class)->findBy(['userWon' => $user]);
        }
        
        
        return $this->render('front_office/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'markets' => $markets,
            'voucher' => $voucher,
        ]);
    }
}
