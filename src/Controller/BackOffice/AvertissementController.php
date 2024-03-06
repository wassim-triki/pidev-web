<?php

namespace App\Controller\BackOffice;

use App\Entity\Avertissement;
use App\Repository\AvertissementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;



class AvertissementController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    #[Route('/listAvertissement', name: 'listAvertissement')]
    public function listAvertissement(AvertissementRepository $repaverti, Request  $request, PaginatorInterface $paginatorInterface): Response
    {
        // Only allow authenticated users with the ROLE_ADMIN to access this page
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $list = $repaverti->findAll();
        $pagination = $paginatorInterface->paginate(
            $list,
            $request->query->getInt('page', 1),
            2
        );


        return $this->render('back_office/dashboard/listeavertissement1.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/avertissement/confirm/{id}', name: 'avertissement_confirm')]
    public function confirmAvertissement(
        int $id,
        AvertissementRepository $avertissementRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UserRepository $userRepository // Injectez le repository de l'entité User
    ): Response {
        $avertissement = $avertissementRepository->find($id);

        if (!$avertissement) {
            throw $this->createNotFoundException('Avertissement non trouvé');
        }

        // Marquer l'avertissement comme confirmé
        $avertissement->setConfirmation(true);
        $entityManager->flush();

        // Récupérer le nom d'utilisateur associé à l'avertissement
        $reportedUsername = $avertissement->getReportedUsername();

        // Récupérer l'utilisateur associé à partir du nom d'utilisateur
        $user = $userRepository->findOneBy(['username' => $reportedUsername]);

        if (!$user) {
            throw new \Exception('Utilisateur non trouvé pour le nom d\'utilisateur associé à l\'avertissement.');
        }

        // Incrémenter le nombre d'avertissements de l'utilisateur
        $nombreAvertissements = $user->getAvertissementsCount() ?? 0;
        $user->setAvertissementsCount($nombreAvertissements + 1);
        $entityManager->flush();

        // Envoi de l'email à l'utilisateur signalé
        $email = (new Email())
            ->from('no-reply@al9ani.tn')
            ->to($user->getEmail())
            ->subject('Nouvel avertissement')
            ->html("Cher utilisateur, vous avez reçu un nouvel avertissement. Vous avez maintenant {$user->getAvertissementsCount()} avertissements au total.");

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Log or handle the error as needed
            $this->addFlash('error', 'Failed to send email: ' . $e->getMessage());
        }

        // Rediriger ou retourner une réponse appropriée
        $this->addFlash('success', 'Warning confirmed successfully');
        return $this->redirectToRoute('listAvertissement');
    }
    #[Route('/avertissement/delete/{id}', name: 'avertissement_delete')]
    public function deleteAvertissement(
        int $id,
        AvertissementRepository $avertissementRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $avertissement = $avertissementRepository->find($id);



        $entityManager->remove($avertissement);
        $entityManager->flush();

        $this->addFlash(
            'success', // Type de message, peut être 'success', 'warning', 'error', etc.
            'The warning has been successfully deleted' // Le message à afficher
        );
        return $this->redirectToRoute('listAvertissement');
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request, AvertissementRepository $repaverti): JsonResponse
    {
        $query = $request->query->get('query');

        // Perform search query using Doctrine ORM
        $results = $repaverti->createQueryBuilder('e')
            ->where('e.ReportedUsername LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        // Transform results to array to prepare for JSON response
        $formattedResults = [];
        foreach ($results as $result) {
            // Customize the fields you want to include in the response
            $formattedResults[] = [
                'ReportedUsername ' => $result->getReportedUsername(),
                'confirmation' => $result->isConfirmation(),
                'screenShot' => $result->getScreenShot(),
                'raison' => $result->getRaison(),


                // Add more fields as needed
            ];
        }

        // Return JSON response
        return new JsonResponse($formattedResults);
    }


    public function dashboard(AvertissementRepository $avertissementRepository): Response
    {
        $totalAvertissements = $avertissementRepository->countTotalAvertissements();
        $confirmedAvertissements = $avertissementRepository->countConfirmedAvertissements();

        // Récupérez d'autres statistiques si nécessaire

        return $this->render('back_office/dashboard/dashboard.html.twig', [
            'totalAvertissements' => $totalAvertissements,
            'confirmedAvertissements' => $confirmedAvertissements,
            // Passez d'autres statistiques à la vue
        ]);
    }
}
