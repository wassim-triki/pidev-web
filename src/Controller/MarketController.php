<?php

namespace App\Controller;

use App\Entity\Market;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MarketType;

class MarketController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/market', name: 'market_index')]
    public function index(): Response
    {
        return $this->render('market/index.html.twig', [
            'markets' => $this->managerRegistry->getRepository(Market::class)->findAll(),
        ]);
    }

    #[Route('/showmarket', name: 'market_show', methods: ['GET'])]
    public function show(Market $market): Response
    {
        $market = $this->managerRegistry->getRepository(Market::class)->find($market);
        return $this->render('market/show.html.twig', ['market' => $market,]);
    }

    #[Route('/newmarket', name: 'market_new', methods: ['GET', 'POST'])]
    public function newMarket(Request $request): Response
    {
        $market = new Market();
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($market);
            $entityManager->flush();

            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/new.html.twig', [
            'market' => $market,
            'form' => $form->createView(),
        ]);
    }
}