<?php

namespace App\Controller\FrontOffice;

use App\Entity\Postcommentaire;
use App\Entity\PostGroup;
use App\Entity\Sponsoring;
use App\Form\FormCommentaireType;
use App\Form\PostGroupType;
use App\Repository\PostGroupRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use \ConsoleTVs\Profanity\Builder;

class PostGroupController extends AbstractController
{


    #[Route('/addpostgroup/{id}', name: 'addpostgroup')]
    public function addpostgroup(ManagerRegistry $managerRegistry, PostGroupRepository $PostGroupRepository, UserRepository $userRepository, Request $request, int $id, Security $security): Response
    {
        $em = $managerRegistry->getManager();

        // Récupérer l'objet Sponsoring associé à l'ID
        $sponsoring = $this->getDoctrine()->getRepository(Sponsoring::class)->find($id);;

        // Vérifier si le sponsoring existe
        if (!$sponsoring) {
            throw $this->createNotFoundException('Sponsoring non trouvé pour l\'ID ' . $id);
        }

        // Créer un nouveau post
        $post = new PostGroup();
        $post->setSponsoring($sponsoring); // Un nouveau post est créé et associé au sponsoring récupéré.

        // Récupérer l'utilisateur actuellement authentifié et l'associer au post
        $user = $security->getUser();
        $post->setUser($user);

        $postUser = $userRepository->findOneBy(array("id" => $user->getUserIdentifier()));


        $form = $this->createForm(PostGroupType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $dictionaryPath = '/path/to/dictionary/file.txt';

            // Set the dictionary for the Builder class
            Builder::setDictionary($dictionaryPath);
            */
            $content = $post->getContenu();
            $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $post->setContenu($cleanedContenu);
            $currentDate = new \DateTime();
            $post->setDate($currentDate);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre poste a été ajouté');
            return $this->redirectToRoute('addpostgroup', ['id' => $id]);
        }
        //$showpost = $sponsoring->getPostgroup(); 
        $sponsoringImage = $sponsoring->getImage();
        // Récupérer uniquement les posts associés à ce sponsoring spécifique
        $showpost = $PostGroupRepository->findPostsBySponsoringOrderedByDate($id);


        return $this->renderForm('front_office/post_group/group.html.twig', [
            'f' => $form,
            'showpost' => $showpost,
            'sponsoringImage' => $sponsoringImage,
            'id' => $id
        ]);
    }



    #[Route('/showdbpostgroup', name: 'showdbpostgroup')]
    public function showdbpostgroup(PostGroupRepository $PostGroupRepository, Request $request): Response
    {
        $post = $PostGroupRepository->findAll();


        return $this->renderForm('front_office/post_group/showdbpostgroup.html.twig', [
            'post' => $post
        ]);
    }


    #[Route('/addpostgroup/{id}/{idpost}', name: 'editpostgroup')]
    public function editpostgroup($idpost, $id, PostGroupRepository $PostGroupRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();

        // Récupérer le post à éditer
        $post = $PostGroupRepository->find($idpost);

        // Vérifier si le post existe
        if (!$post) {
            throw $this->createNotFoundException('Post non trouvé pour l\'ID ' . $idpost);
        }

        // Créer le formulaire d'édition du post
        $form = $this->createForm(PostGroupType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le post a été modifié avec succès.');


            return $this->redirectToRoute('addpostgroup', ['id' => $id]);
        }

        return $this->render('front_office/post_group/editpost.html.twig', [
            'form' => $form->createView(),
            'postId' => $idpost,
            'id' => $id // Passer l'ID du post à la vue
        ]);
    }



    #[Route('/addpostgroup/{id}/{idpost}/delete', name: 'deletepostgroup')]
    public function deletepost($id, $idpost, PostGroupRepository $PostGroupRepository, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $PostGroupRepository->find($idpost);
        $em->remove($dataid);
        $em->flush();
        $this->addFlash('success', 'Votre post a été suprimé');
        return $this->redirectToRoute('addpostgroup', ['id' => $id]);
    }
}
