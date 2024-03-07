<?php

namespace App\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Market;
use App\Entity\Voucher;

class HomeController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $post = $postRepository->findAll();
        $post = $paginator->paginate(
            $post, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );
        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        $user = $this->getUser();
        if($user){
            $result=$postRepository->GetLostAndFoundPostCount($user->getId());
            $counts = $result[0];
            $lostCount = $counts['lost_count'];
            $foundCount = $counts['found_count'];

        }
        $voucher = null;
        if ($user) {
            $voucher = $this->managerRegistry->getRepository(Voucher::class)->findBy(['userWon' => $user]);
        }

        
        return $this->render('front_office/home/index.html.twig', [
            'post' => $post,
            'markets' => $markets,
            'voucher' => $voucher,
            'lostCount' =>$lostCount,
            'foundCount' => $foundCount
        ]);
    }
}
