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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;

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
    public function newMarket(Request $request,SluggerInterface $slugger): Response
    {
        $market = new Market();
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
             $photoFile = $form->get('image')->getData(); // Assuming 'photo' is the field name in your form
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $market->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle error
                }
            }
            $address = $market->getRegion() . ' - ' . $market->getCity() . ' - ' . $market->getZipCode();
            $market->setAddress($address);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($market);
            $entityManager->flush();

            return $this->redirectToRoute('admin-market-list');
        }

        return $this->render('market/newMarket.html.twig', [
            'market' => $market,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/market/{id}', name: 'market_edit', methods: ['GET', 'POST'])]
    public function editMarket(Request $request, Market $market, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('image')->getData(); // Assuming 'photo' is the field name in your form
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $market->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle error
                }
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin-market-list', ['id' => $market->getId()]);
        }

        return $this->render('market/editMarket.html.twig', [
            'market' => $market,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deletemarket/{id}', name: 'deletemarket')]
    public function deleteroom($id,MarketRepository $marketRepository,ManagerRegistry $managerRegistry): Response {
        $em = $managerRegistry->getManager();
        $dataid = $marketRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('admin-market-list');
    }

    #[Route('/back-to-index', name: 'back_to_index')]
    public function backToIndex(): Response
    {
        return $this->redirectToRoute('showmarket');
    }

    #[Route('/market-details/{id}', name: 'market_details')]
    public function details($id, EntityManagerInterface $entityManager): Response
    {
        // Fetch voucher details from the database
        $market = $entityManager->getRepository(Market::class)->find($id);

        // Check if voucher exists
        if (!$market) {
            // Return error response if voucher is not found
            return new Response('Market not found', Response::HTTP_NOT_FOUND);
        }

        // Render the Twig template with voucher details
        return $this->render('backOffice/Dashboard/dash-market-listing.html.twig', [
            'market' => $market,
        ]);
    }
}