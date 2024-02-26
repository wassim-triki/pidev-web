<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Voucher;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VoucherType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\VoucherRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
class VoucherController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/voucher', name: 'app_voucher')]
    public function index(): Response
    {
        return $this->render('frontOffice/error404.html.twig', [
            'voucher' => $this->managerRegistry->getRepository(Voucher::class)->findAll(),
        ]);
    }

    #[Route('/voucher/add', name: 'voucher_add')]
    public function add(Request $request): Response
    {
        // Create a new Voucher object
        $voucher = new Voucher();

        // Create and handle the form
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $voucher->setUsageLimit(1);
            $voucher->setCode($this->generateRandomString());
            // Persist the new voucher to the database
            $entityManager->persist($voucher);
            $entityManager->flush();
            return $this->redirectToRoute('app_voucher');
        }

        // Render the form template with the form
        return $this->render('voucher/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voucher/{id}/edit', name: 'voucher_edit', methods: ['GET', 'POST'])]
    public function editVoucherCategory(Request $request, Voucher $voucher): Response
    {
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($voucher);
            $entityManager->flush();
            return $this->redirectToRoute('app_voucher');
        }

        return $this->render('voucher/editVoucher.html.twig', [
            'voucher' => $voucher,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/voucher/{id}', name: 'voucher_delete', methods: ['GET','post'])]
    public function deleteVoucher($id, VoucherRepository $voucherRepository, ManagerRegistry $managerRegistry): RedirectResponse
    {
        $entityManager = $managerRegistry->getManager();
        $voucher = $voucherRepository->find($id);
    
        if (!$voucher) {
            throw $this->createNotFoundException('Voucher not found');
        }
    
        $entityManager->remove($voucher);
        $entityManager->flush();
    
        return $this->redirectToRoute('admin-voucher-list');
    }
    
    private function generateRandomString($length = 14) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        $segments = [6, 4, 6]; // Lengths of segments

        foreach ($segments as $segmentLength) {
            $segment = '';
            for ($i = 0; $i < $segmentLength; $i++) {
                $segment .= $characters[rand(0, strlen($characters) - 1)];
            }
            $code .= $segment . '-';
        }

        // Remove the last hyphen
        $code = rtrim($code, '-');

        return $code;
    }

    #[Route('/confirm-voucher',name: 'confirm_voucher')]
    public function confirmVoucher(Request $request): Response
    {
        return $this->render('frontOffice/confirmVoucher.html.twig');
    }

    #[Route('/use-voucher/{voucherId}', name: 'use_voucher', methods: ['POST'])]
    public function useVoucher(Request $request, $voucherId): Response {
        // Retrieve the voucher entity from the database
        $entityManager = $this->managerRegistry->getManager();
        $voucher = $entityManager->getRepository(Voucher::class)->find($voucherId);
        
        // Check if the voucher exists
        if (!$voucher) {
            throw $this->createNotFoundException('Voucher not found');
        }
        
        // Update the isValid property to 0
        $voucher->setIsValid(false); // Assuming `false` means invalid in your database
        
        // Persist the changes to the database
        $entityManager->flush();
        
        // Return a JSON response indicating success
        return $this->json(['success' => true]);
    }

    #[Route('/filter-vouchers', name: 'filter_vouchers')]
    public function filterVouchers(Request $request)
    {
        $filter = $request->query->get('filter');

        // Get the entity manager from the manager registry
        $entityManager = $this->managerRegistry->getManager();

        // Fetch vouchers based on the selected filter
        if ($filter === 'used') {
            $vouchers = $entityManager->getRepository(Voucher::class)->findBy(['isValid' => false]);
        } else {
            $vouchers = $entityManager->getRepository(Voucher::class)->findBy(['isValid' => true]);
        }

        // Render the voucher listing template with the filtered vouchers
        return $this->render('voucher/voucher_listing.html.twig', [
            'vouchers' => $vouchers
        ]);
    }

    #[Route('/search-vouchers-by-category', name: 'search_vouchers_by_category')]
    public function searchVouchersByCategory(Request $request): JsonResponse
    {
        $category = $request->query->get('category');

        // Perform the search for vouchers by category
        $entityManager = $this->managerRegistry->getManager();
        $vouchers = $entityManager->getRepository(Voucher::class)->findByCategory($category);

        // Serialize the vouchers to JSON format
        $data = [];
        foreach ($vouchers as $voucher) {
            $data[] = [
                'value' => $voucher->getValue(),
                // Add other voucher properties as needed
            ];
        }

        return new JsonResponse($data);
    }
    #[Route('/voucher-details/{id}', name: 'voucher_details')]
    public function details($id, EntityManagerInterface $entityManager): Response
    {
        // Fetch voucher details from the database
        $voucher = $entityManager->getRepository(Voucher::class)->find($id);

        // Check if voucher exists
        if (!$voucher) {
            // Return error response if voucher is not found
            return new Response('Voucher not found', Response::HTTP_NOT_FOUND);
        }

        // Render the Twig template with voucher details
        return $this->render('backOffice/Dashboard/dash-voucher-listing.html.twig', [
            'voucher' => $voucher,
        ]);
    }
}

