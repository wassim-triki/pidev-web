<?php

namespace App\Controller;

use App\Entity\Market;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MarketRepository;

class HomeController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Fetch all markets using ManagerRegistry
        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        
        return $this->render('home/index.html.twig', [
            'markets' => $markets, // Pass the markets to the template
        ]);
    }

    #[Route('/search', name: 'search_market')]
    public function search(Request $request, MarketRepository $marketRepository): Response
    {
        $name = $request->query->get('name');
        $region = $request->query->get('region');

        $markets = $marketRepository->findBySearchCriteria($name, $region);

        return $this->render('market/search_results.html.twig', [
            'markets' => $markets,
        ]);
    }
}
