<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard()
    {
        // Only allow authenticated users with the ROLE_ADMIN to access this page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('back_office/dashboard/dashboard.html.twig');
    }
}
