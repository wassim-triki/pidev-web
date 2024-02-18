<?php

namespace App\Controller;

use App\Entity\Market;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MarketType;
use App\Repository\MarketRepository;

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
        return $this->render('market/showMarket.html.twig', [
            'markets' => $this->managerRegistry->getRepository(Market::class)->findAll(),
        ]);
    }

    #[Route('/market/showmarket', name: 'market_show', methods: ['GET'])]
    public function show(Market $market): Response
    {
        $market = $this->managerRegistry->getRepository(Market::class)->findAll($market);
        return $this->render('market/show.html.twig', ['market' => $market,]);
    }

    #[Route('/market/showmarkets', name: 'showmarket')]
    public function showMarket(MarketRepository $marketRepository): Response
    {
        $market = $marketRepository->findAll();
        return $this->render('market/showMarket.html.twig', [
            'markets' => $market
        ]);
    }

    #[Route('/market/{id}/details', name: 'market_details')]
    public function showDetails(Market $market): Response
    {
        $entityManager = $this->managerRegistry->getManagerForClass(Market::class);
        $marketWithVouchers = $entityManager->getRepository(Market::class)->find($market->getId());

        return $this->render('market/details.html.twig', [
            'market' => $marketWithVouchers,
        ]);
    }

    #[Route('/market/newmarket', name: 'market_new', methods: ['GET', 'POST'])]
    public function newMarket(Request $request): Response
    {
        $market = new Market();
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $market->getRegion() . ' - ' . $market->getCity() . ' - ' . $market->getZipCode();
            $market->setAddress($address);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($market);
            $entityManager->flush();

            return $this->redirectToRoute('showmarket');
        }

        return $this->render('market/newMarket.html.twig', [
            'market' => $market,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/market/{id}', name: 'market_edit', methods: ['GET', 'POST'])]
    public function editMarket(Request $request, Market $market): Response
    {
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('showmarket', ['id' => $market->getId()]);
        }

        return $this->render('market/editMarket.html.twig', [
            'market' => $market,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deletemarket/{id}', name: 'deletemarket')]
    public function deleteroom($id,MarketRepository $postRepository,ManagerRegistry $managerRegistry): Response {
        $em = $managerRegistry->getManager();
        $dataid = $postRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showmarket');
    }

    #[Route('/back-to-index', name: 'back_to_index')]
    public function backToIndex(): Response
    {
        return $this->redirectToRoute('showmarket');
    }
}