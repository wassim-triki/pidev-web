<?php

namespace App\Controller;

use App\Controller\Service\SmsService;
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
use Symfony\Component\HttpClient\HttpClient;
use HTTP_Request2;

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
        return $this->render('front_office/error/error404.html.twig', [
            'markets' => $this->managerRegistry->getRepository(Market::class)->findAll(),
        ]);
    }

    #[Route('market/showmarkets', name: 'showmarket')]
    public function showMarket(MarketRepository $marketRepository): Response
    {
        return $this->render('front_office/error/error404.html.twig');
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
            
            $client = HttpClient::create();

            $response = $client->request('POST', 'https://ggeryw.api.infobip.com/sms/2/text/advanced', [
                'headers' => [
                    'Authorization' => 'App 89e1c61117eca29bb00decebebd55d87-1a217764-058b-4b49-b5bb-70c536865ec8',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => [
                    'messages' => [
                        [
                            'destinations' => [
                                ['to' => '21658450148']
                            ],
                            'from' => 'ServiceSMS',
                            'text' => 'MARKET NAME :'.$market->getName().' - MARKET ADDRESS : ' . $market->getAddress() . ' add with success '.' Thank you for your trust.',
                        ]
                    ]
                ]
            ]);
            
            $status = $response->getStatusCode();
            if ($status === Response::HTTP_OK) {
                echo $response->getContent();
            } else {
                echo 'Unexpected HTTP status: ' . $status;
            }
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($market);
            $entityManager->flush();

            return $this->redirectToRoute('admin-market-list');
        }

        return $this->render('front_office/market/newMarket.html.twig', [
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

        return $this->render('front_office/market/editMarket.html.twig', [
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
        return $this->render('back_office/crm/dash-market-listing.html.twig', [
            'market' => $market,
        ]);
    }
}