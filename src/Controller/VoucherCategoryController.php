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
use Doctrine\ORM\EntityManagerInterface;

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
        return $this->render('front_office/error/error404.html.twig', [
            'voucher' => $this->managerRegistry->getRepository(VoucherCategory::class)->findAll(),
        ]);
    }

    #[Route('vouchers/voucher_category/newvouchercategory', name: 'vouchercategory_new', methods: ['GET', 'POST'])]
    public function newMarket(Request $request): Response
    {
        $voucherCategory = new VoucherCategory();
        $form = $this->createForm(VoucherCategoryType::class, $voucherCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($voucherCategory);
            $entityManager->flush();

            return $this->redirectToRoute('admin-category-list');
        }

        return $this->render('/front_office/voucher_category/newVoucherCategory.html.twig', [
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
            return $this->redirectToRoute('admin-category-list');
        }

        return $this->render('front_office/voucher_category/editVoucherCategory.html.twig', [
            'voucherCategory' => $voucherCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voucher_category/{id}', name: 'vouchercategory_delete', methods: ['GET','post'])]
    public function deleteVoucherCategory($id, VoucherCategoryRepository $voucherCategoryRepository, ManagerRegistry $managerRegistry): RedirectResponse
    {
        $entityManager = $managerRegistry->getManager();
        $voucherCategory = $voucherCategoryRepository->find($id);
    
        if (!$voucherCategory) {
            throw $this->createNotFoundException('Voucher category not found');
        }
    
        $entityManager->remove($voucherCategory);
        $entityManager->flush();
    
        return $this->redirectToRoute('admin-category-list');
    }

    #[Route('/voucher_category/{id}/details', name: 'vouchercategory_details')]
    public function showDetails(VoucherCategory $vouchercategory): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(VoucherCategory::class);
        $voucherCategory = $entityManager->getRepository(VoucherCategory::class)->find($vouchercategory->getId());

        return $this->render('/front_office/voucher_category/detailsVoucher.html.twig', [
            'voucherCategory' => $voucherCategory,
        ]);
    }

    #[Route('/category-details/{id}', name: 'category_details')]
    public function details($id, EntityManagerInterface $entityManager): Response
    {
        // Fetch voucher details from the database
        $category = $entityManager->getRepository(VoucherCategory::class)->find($id);

        // Check if voucher exists
        if (!$category) {
            // Return error response if voucher is not found
            return new Response('Category not found', Response::HTTP_NOT_FOUND);
        }

        // Render the Twig template with voucher details
        return $this->render('back_office/crm/dash-category-listing.html.twig', [
            'category' => $category,
        ]);
    }

}
