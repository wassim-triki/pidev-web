<?php

namespace App\Controller\FrontOffice;

use App\Entity\Postcommentaire;
use App\Entity\PostGroup;
use App\Form\FormCommentaireType;
use App\Repository\PostcommentaireRepository;
use App\Repository\PostGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentaireController extends AbstractController
{

    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('front_office/commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/postgroup/{id}/{idpost}/commentaire/add', name: 'commentaire_add', methods: ["POST"])]
    public function addCommentaire($id, $idpost, PostGroupRepository $PostGroupRepository, Request $request, Security $security): Response
    {
        // Récupérer le contenu du commentaire
        $contenu = $request->request->get('contenu');

        $postGroup = $PostGroupRepository->find($idpost);
        // Récupérer l'utilisateur actuellement authentifié
        $user = $security->getUser();

        // Créer une nouvelle instance de Postcommentaire
        $commentaire = new Postcommentaire();
        $commentaire->setCommentaire($contenu);
        $commentaire->setPostGroup($postGroup);
        $commentaire->setUser($user); // Définir l'utilisateur sur le commentaire
         /*
            $dictionaryPath = '/path/to/dictionary/file.txt';

            // Set the dictionary for the Builder class
            Builder::setDictionary($dictionaryPath);
            */
            $content = $commentaire->getCommentaire();
            $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $commentaire->setCommentaire($cleanedContenu);
            
        // Enregistrer le commentaire en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();
        $this->addFlash('success', 'Le commentaire a été ajouté avec succès.');

        // Rediriger vers une autre page après l'ajout du commentaire
        return $this->redirectToRoute('addpostgroup', ['id' => $id]);
    }




    #[Route('/postgroup/{id}/{idpost}/commentaire/delete/{commentId}', name: 'commentaire_delete')]
    public function deleteCommentaire($id, $commentId, PostcommentaireRepository $pcRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $pcRepository->find($commentId);
        $em->remove($dataid);
        $em->flush();
        $this->addFlash('success', 'Votre post a été suprimé');
        return $this->redirectToRoute('addpostgroup', ['id' => $id]);
    }



    #[Route('/postgroup/{id}/{idpost}/commentaire/edit/{commentId}', name: 'commentaire_edit')]
    public function editpostgroup($id, $commentId, PostcommentaireRepository $pcRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        // Récupérer le commentaire à éditer
        $commentaire = $pcRepository->find($commentId);

        // Vérifier si le post existe
        if (!$commentaire) {
            throw $this->createNotFoundException('Post non trouvé pour l\'ID ' . $commentId);
        }

        // Créer le formulaire d'édition du post
        $form = $this->createForm(FormCommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /*
            $dictionaryPath = '/path/to/dictionary/file.txt';

            // Set the dictionary for the Builder class
            Builder::setDictionary($dictionaryPath);
            */
            $content = $commentaire->getCommentaire();
            $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $commentaire->setCommentaire($cleanedContenu);
            $em->flush();
            $this->addFlash('success', 'Le post a été modifié avec succès.');

            return $this->redirectToRoute('addpostgroup', ['id' => $id]);
        }

        return $this->render('front_office/post_group/editcommentaire.html.twig', [
            'form' => $form->createView(),
            'commentaireId' => $commentId, // Passer l'ID du post à la vue 
        ]);
    }




    #[Route('/like-comment/{commentId}', name: 'like_comment', methods: ['POST'])]
    public function likeComment(int $commentId, Request $request, EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        $user = $security->getUser();

        // Check if the user is authenticated
        if (!$user instanceof UserInterface) {
            return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Retrieve the comment
        $comment = $entityManager->getRepository(Postcommentaire::class)->find($commentId);

        if (!$comment) {
            return new JsonResponse(['error' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if the user already liked the comment
        $likedBy = $comment->getLikedBy();
        if ($comment->isLikedByUser($user)) {
            // User already liked the comment, remove their like
            $comment->decrementLikes();
            $likedBy = array_diff($likedBy, [$user->getUserIdentifier()]);
        } else {
            // User has not liked the comment, add their like
            $comment->incrementLikes();
            $likedBy[] = $user->getUserIdentifier();
        }

        // Update the likedBy attribute
        $comment->setLikedBy($likedBy);

        // Persist the changes
        $entityManager->flush();

        // Return the response with the updated likes count
        return new JsonResponse(['likes' => $comment->getLikes()]);
    }




    #[Route('/dislike-comment/{commentId}', name: 'dislike_comment', methods: ['POST'])]
    public function dislikeComment(int $commentId, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        // Vérifiez si l'utilisateur est authentifié
        if (!$user) {
            return new Response('User not authenticated', Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer le commentaire à partir de l'ID
        $comment = $entityManager->getRepository(Postcommentaire::class)->find($commentId);

        // Vérifiez si le commentaire existe
        if (!$comment) {
            return new Response('Comment not found', Response::HTTP_NOT_FOUND);
        }

        // Récupérer la liste des utilisateurs qui ont aimé le commentaire
        $likedBy = $comment->getLikedBy();

        // Vérifiez si l'utilisateur a déjà aimé le commentaire
        if (!in_array($user->getUserIdentifier(), $likedBy)) {
            return new Response('You have not liked this comment', Response::HTTP_FORBIDDEN);
        }

        // Supprimez le like de l'utilisateur
        $key = array_search($user->getUserIdentifier(), $likedBy);
        unset($likedBy[$key]);

        // Mettez à jour la liste des utilisateurs qui ont aimé le commentaire
        $comment->setLikedBy($likedBy);

        // Décrémentez le nombre total de likes
        $comment->setLikesCount($comment->getLikesCount() - 1);

        // Enregistrez les modifications
        $entityManager->flush();

        return new Response('Disliked comment', Response::HTTP_OK);
    }
}
