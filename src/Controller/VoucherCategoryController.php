<?php

namespace App\Controller;

use App\Entity\VoucherCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VoucherCategoryType;

class VoucherCategoryController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }


    #[Route('/voucher/category', name: 'app_voucher_category')]
    public function index(): Response
    {
        return $this->render('voucher_category/index.html.twig', [
            'voucher' => $this->managerRegistry->getRepository(VoucherCategory::class)->findAll(),
        ]);
    }

    #[Route('/voucher_category/newvouchercategory', name: 'vouchercategory_new', methods: ['GET', 'POST'])]
    public function newMarket(Request $request): Response
    {
        $voucherCategory = new VoucherCategory();
        $form = $this->createForm(VoucherCategoryType::class, $voucherCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($voucherCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_voucher_category');
        }

        return $this->render('voucher_category/newVoucherCategory.html.twig', [
            'voucherCategory' => $voucherCategory,
            'form' => $form->createView(),
        ]);
    }
}
