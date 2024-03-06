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


class CrmController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/crm/vouchers', name: 'admin-voucher-list')]
    public function voucherList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $vouchers = $this->managerRegistry->getRepository(Voucher::class)->findAll();
         $pagination = $paginator->paginate(
            $vouchers,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('back_office/crm/crm-voucher-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }

    #[Route('/crm/markets', name: 'admin-market-list')]
    public function marketList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $market = $this->managerRegistry->getRepository(Market::class)->findAll();
         $pagination = $paginator->paginate(
            $market,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('back_office/crm/crm-market-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }

    #[Route('/crm/categories', name: 'admin-category-list')]
    public function categoryList(PaginatorInterface $paginator, Request $request): Response
    {
         // Fetch voucher details from the database
         $category = $this->managerRegistry->getRepository(VoucherCategory::class)->findAll();
         $pagination = $paginator->paginate(
            $category,
            $request->query->getInt('page', 1),
            5 
        );
         return $this->render('back_office/crm/crm-category-list.html.twig', [
             'pagination' => $pagination,
         ]);
    }
}
