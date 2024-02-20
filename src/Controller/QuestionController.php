<?php
namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'app_question')]
    public function index(): Response
    {
        return $this->render('question/show.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    
    #[Route('/showquestions', name: 'show_questions')]
    public function show(QuestionRepository $qr): Response
    {
         $question= $qr->findAll();
        return $this->render('question/show.html.twig', [
            'questions' => $question
        ]);
    }

    #[Route('/addquestion', name: 'add_question')]
     
    public function addquestion(Request $request,ManagerRegistry $managerRegistry): Response
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

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * #Route("/question/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    #[Route('/question/{id}/edit', name: 'edit_question')]
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * #Route("/question/{id}", name="question_delete", methods={"DELETE"})
     */
    #[Route('/question/{id}', name: 'delete_question')]
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }
}
