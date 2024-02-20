<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Voucher;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\VoucherCategory;
use App\Entity\Market;
use App\Form\VoucherType;
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
        return $this->render('voucher/index.html.twig', [
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
            // $code = hash('sha256', $marketName . $categoryName . $value);
            // $voucher->setCode($code);
            $entityManager = $this->managerRegistry->getManager();

            // Persist the new voucher to the database
            $entityManager->persist($voucher);
            $entityManager->flush();

            // Redirect to a success page or render a success message
            return $this->redirectToRoute('voucher_list');
        }

        // Render the form template with the form
        return $this->render('voucher/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

