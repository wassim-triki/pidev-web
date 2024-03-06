<?php

namespace App\Controller\BackOffice;

use App\Entity\Sponsoring;
use App\Form\FormSponsoringType;
use App\Repository\AvertissementRepository;
use App\Repository\SponsoringRepository;
use App\Service\JwtTokenService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SponsoringAdminController extends AbstractController
{
    #[Route('/posts', name: 'posts')]
    public function showSponsorByAdmin(AvertissementRepository $repaverti, JwtTokenService $sponsorStatisticsService,  SponsoringRepository $postRepository): Response
    {
        $statistics = $sponsorStatisticsService->getSponsorStatistics();
        $statisticsPost = $sponsorStatisticsService->getPostStatistics();
        $statistiques = $repaverti->countAvertissementsByRaison();
        $n1 = $repaverti->countraisoninappropriatecontent1();
        $n2 = $repaverti->countraisoninappropriatecontent2();
        $n3 = $repaverti->countraisoninappropriatecontent3();
        return $this->render('back_office\dashboard\dashboard.html.twig', [
            'statistics' => $statistics,
            'postStatistics' => $statisticsPost,
            'statistiques' => $statistiques,
            'n1' => $n1,
            'n2' => $n2,
            'n3' => $n3,
        ]);
    }

    #[Route('/addformsponsor', name: 'addformsponsor')]
    public function addformsponsor(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
    {
        $em = $managerRegistry->getManager();
        $sponsoring = new Sponsoring();
        $form = $this->createForm(FormSponsoringType::class, $sponsoring);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $photoFile */
            $photoFile = $form->get('image')->getData(); // Assuming 'photo' is the field name in your form

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Use the slugger to create a safe filename
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $sponsoring->setImage($newFilename); // Assuming you store just the filename; adjust if storing paths
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }

            $em->persist($sponsoring);
            $em->flush();


            $this->addFlash('success', 'Votre sponsor a été ajouté');

            return $this->redirectToRoute('showdbsponsoring');
        }

        return $this->renderForm('back_office/sponsoring/addform.html.twig', [
            'f' => $form,
        ]);
    }





    #[Route('/showdbsponsoring', name: 'showdbsponsoring')]
    public function showdbsponsoring(SponsoringRepository $sponsorRepository, Request $request): Response
    {
        $sponsor = $sponsorRepository->findAll();


        return $this->renderForm('back_office/sponsoring/showdbsponsoring.html.twig', [
            'sponsor' => $sponsor
        ]);
    }




    #[Route('/editsponsor/{id}', name: 'editsponsor')]
    public function editsponsor($id, SponsoringRepository $sponsorRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        $dataid = $sponsorRepository->find($id);

        $form = $this->createForm(FormSponsoringType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            $this->addFlash('success', 'Votre sponsor a été modifié');
            return $this->redirectToRoute('showdbsponsoring');
        }

        return $this->renderForm('back_office/sponsoring/editsponsor.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/deletesponsor/{id}', name: 'deletesponsor')]
    public function deletesponsor($id, SponsoringRepository $sponsorRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $sponsor = $sponsorRepository->find($id);

        // Check if the sponsor exists
        if (!$sponsor) {
            throw $this->createNotFoundException('Sponsor not found');
        }

        // Get the related post groups
        $postGroups = $sponsor->getPostGroup();

        // Remove each related post group
        foreach ($postGroups as $postGroup) {
            $em->remove($postGroup);
        }

        // Now remove the sponsor
        $em->remove($sponsor);

        // Flush changes
        $em->flush();
        $this->addFlash('success', 'Votre sponsor a été suprimé');

        return $this->redirectToRoute('showdbsponsoring');
    }


    // Ajoutez cette méthode dans votre contrôleur Symfony
    #[Route('/update-sponsor-state', name: 'update_sponsor_state', methods: ['POST'])]
    public function updateSponsorState(Request $request, SponsoringRepository $sponsorRepository, ManagerRegistry $managerRegistry): JsonResponse
    {
        $sponsorId = $request->request->get('id');
        $newState = $request->request->get('type');

        // Vérifiez si l'ID du sponsor est valide
        $sponsor = $sponsorRepository->find($sponsorId);
        if (!$sponsor) {
            return new JsonResponse(['message' => 'Sponsor not found'], Response::HTTP_NOT_FOUND);
        }

        // Mettez à jour l'état du sponsor
        $sponsor->setType($newState);

        // Enregistrez les modifications dans la base de données
        $entityManager = $managerRegistry->getManager();
        $entityManager->flush();

        // Répondez avec succès
        return new JsonResponse(['message' => 'Sponsor state updated successfully'], Response::HTTP_OK);
    }
}
