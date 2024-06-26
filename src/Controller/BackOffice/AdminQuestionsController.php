<?php

namespace App\Controller\BackOffice;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Entity\Answer;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


class AdminQuestionsController extends AbstractController
{
    #[Route('/questions', name: 'app_admin_questions')]
    public function index(): Response
    {
        return $this->render('back_office/admin_questions/show.html.twig', [
            'controller_name' => 'AdminQuestionsController',
        ]);
    }
    #[Route('/showquestions', name: 'admin_questions_show')]
    public function show(QuestionRepository $questionRepository, PaginatorInterface $paginator, Request $req): Response
    {
        $pagination = $paginator->paginate(
            $questionRepository->paginationquery(),
            $req->query->get('page', 1),
            3

        );
        $totalQuestions = $questionRepository->getTotalQuestionsCount();
        $answeredQuestions = $questionRepository->getAnsweredQuestionsCount();
        $todayQuestions = $questionRepository->getTodayQuestionsCount();


        return $this->render('back_office/admin_questions/show.html.twig', [
            'questions' => $pagination,
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'today_questions' => $todayQuestions,
        ]);
    }
    #[Route('/question/{id}/answer', name: 'admin_answer_new')]
    public function newAnswer(Request $request, ManagerRegistry $managerRegistry, Question $question, MailerInterface $mailer): Response
    {
        $answer = $question->getAnswer();

        if (!$answer) {
            $answer = new Answer();
            $answer->setQuestionId($question);
        }

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $answer->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($answer);
            $entityManager->flush();
            $email = (new TemplatedEmail())
                ->from('admin@Al9ani.tn')
                ->to($question->getUserId()->getEmail())
                ->subject('An admin answered you !')
                ->htmlTemplate('back_office/admin_questions/email.html.twig')

                ->context([
                    'question' => $question,
                    'answer' => $answer,
                    'name' => $question->getUserId()


                ])
            ;

            $mailer->send($email);


            return $this->redirectToRoute('admin_questions_show', ['id' => $question->getId()]);
        }

        return $this->render('back_office/admin_questions/new.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/answer/{id}/delete', name: 'admin_answer_delete')]

    public function deleteAnswer(Answer $answer, ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($answer);
        $entityManager->flush();

        return $this->redirectToRoute('admin_questions_show', ['id' => $answer->getQuestionId()->getId()]);
    }
    #[Route('admin/archivequestion/{id}', name: 'archive_question_admin')]
    public function deletequestion($id, QuestionRepository $qr, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $qr->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('admin_questions_show');
    }

 
    
}

