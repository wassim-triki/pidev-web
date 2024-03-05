<?php

namespace App\Controller\FrontOffice;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'app_question')]
    public function index(): Response
    {
        return $this->render('front_office/question/show.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }


    #[Route('/showquestions', name: 'show_questions')]
    public function show(QuestionRepository $qr, PaginatorInterface $paginator, Request $req): Response
    {
        $pagination = $paginator->paginate(
            $qr->paginationquery(),
            $req->query->get('page', 1),
            3

        );


        return $this->render('front_office/question/show.html.twig', [
            'questions' => $pagination
        ]);
    }

    #[Route('/addquestion', name: 'add_question')]

    public function addquestion(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $entityManager = $managerRegistry->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setUserId($this->getUser());
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('show_questions');
        }

        return $this->render('front_office/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * #Route("/question/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    #[Route('/question/{id}/edit', name: 'edit_question')]
    public function editquestion($id, QuestionRepository $qr, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        // var_dump($id) . die();
        $dataid = $qr->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(QuestionType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('show_questions');
        }

        return $this->renderForm('front_office/question/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deletequestion/{id}', name: 'delete_question')]
    public function deletequestion($id,  QuestionRepository $qr,   ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $qr->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('show_questions');
    }
}
