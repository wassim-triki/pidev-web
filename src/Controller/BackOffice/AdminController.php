<?php

namespace App\Controller\BackOffice;

use App\Entity\Voucher; // Assuming you have a Voucher entity
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MarketRepository;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/dash', name: 'admin_dashboard')]
    public function dashboard()
    {
        return $this->render('backOffice/Dashboard/dashboard.html.twig');
    }

    #[Route('/dash/voucher-list', name: 'admin-voucher-list')]
    public function voucherList(MarketRepository $marketRepository): Response
    {
         // Fetch voucher details from the database
         $vouchers = $this->managerRegistry->getRepository(Voucher::class)->findAll();
         return $this->render('backOffice/Dashboard/crm-voucher-list.html.twig', [
             'vouchers' => $vouchers,
         ]);
    }
}
