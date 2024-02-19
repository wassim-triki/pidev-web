<?php

namespace App\Controller;

use App\Entity\Voucher;
use App\Entity\VoucherCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VoucherCategoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\VoucherCategoryRepository;

class VoucherCategoryController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }


    #[Route('/vouchercategory', name: 'app_voucher_category')]
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

    #[Route('/voucher_category/{id}/edit', name: 'vouchercategory_edit', methods: ['GET', 'POST'])]
    public function editVoucherCategory(Request $request, VoucherCategory $voucherCategory): Response
    {
        $form = $this->createForm(VoucherCategoryType::class, $voucherCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($voucherCategory);
            $entityManager->flush();
            return $this->redirectToRoute('app_voucher_category');
        }

        return $this->render('voucher_category/editVoucherCategory.html.twig', [
            'voucherCategory' => $voucherCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voucher_category/{id}', name: 'vouchercategory_delete', methods: ['post'])]
    public function deleteVoucherCategory($id, VoucherCategoryRepository $voucherCategoryRepository, ManagerRegistry $managerRegistry): RedirectResponse
    {
        $entityManager = $managerRegistry->getManager();
        $voucherCategory = $voucherCategoryRepository->find($id);
    
        if (!$voucherCategory) {
            throw $this->createNotFoundException('Voucher category not found');
        }
    
        $entityManager->remove($voucherCategory);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_voucher_category');
    }

    #[Route('/voucher_category/{id}/details', name: 'vouchercategory_details')]
    public function showDetails(VoucherCategory $vouchercategory): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(VoucherCategory::class);
        $voucherCategory = $entityManager->getRepository(VoucherCategory::class)->find($vouchercategory->getId());

        return $this->render('voucher_category/detailsVoucher.html.twig', [
            'voucherCategory' => $voucherCategory,
        ]);
    }

}
