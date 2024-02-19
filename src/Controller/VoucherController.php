<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoucherController extends AbstractController
{
    #[Route('/voucher', name: 'app_voucher')]
    public function index(): Response
    {
        return $this->render('voucher/index.html.twig', [
            'controller_name' => 'VoucherController',
        ]);
    }
}
