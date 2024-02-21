<?php

namespace App\Controller\FrontOffice;

use App\Entity\Avertissement;
use App\Repository\AvertissementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class AvertissementController extends AbstractController
{


    #[Route('/listAvertissement', name: 'listAvertissement')]
    public function listAvertissement(AvertissementRepository $repaverti): Response
    {
        $list = $repaverti->findAll();

        return $this->render('front_office/avertissement/listAvertissement.html.twig', [
            'list' => $list,
        ]);


    }
    #[Route('/avertissement/confirm/{id}', name: 'avertissement_confirm')]
    public function confirmAvertissement(
        int $id,
        AvertissementRepository $avertissementRepository,
        EntityManagerInterface $entityManager,
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
    
        // Rediriger ou retourner une réponse appropriée
        $this->addFlash('success', 'Avertissement confirmé avec succès.');
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
    
        $this->addFlash('success', 'Avertissement supprimé avec succès.');
        return $this->redirectToRoute('listAvertissement');
    }
}