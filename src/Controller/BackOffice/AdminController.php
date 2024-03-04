<?php

namespace App\Controller\BackOffice;

use App\Entity\Voucher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MarketRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Market;
use App\Entity\VoucherCategory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


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
    public function voucherList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $vouchers = $this->managerRegistry->getRepository(Voucher::class)->findAll();
         $pagination = $paginator->paginate(
            $vouchers,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('backOffice/Dashboard/crm-voucher-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }

    #[Route('/dash/market-list', name: 'admin-market-list')]
    public function marketList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $market = $this->managerRegistry->getRepository(Market::class)->findAll();
         $pagination = $paginator->paginate(
            $market,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('backOffice/Dashboard/crm-market-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }

    #[Route('/dash/category-list', name: 'admin-category-list')]
    public function categoryList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $category = $this->managerRegistry->getRepository(VoucherCategory::class)->findAll();
         $pagination = $paginator->paginate(
            $category,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('backOffice/Dashboard/crm-category-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }
}
