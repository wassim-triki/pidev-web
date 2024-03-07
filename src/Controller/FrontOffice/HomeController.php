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
use App\Enum\CategoryEnum;

class HomeController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/{category}', name: 'app_home', defaults: ['category' => null])]
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator, ?string $category = null): Response
    {
        $query = $category ? $postRepository->findByCategory($category) : $postRepository->findAll();

        $post = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        $user = $this->getUser();
        $voucher = null;
        if ($user) {
            $voucher = $this->managerRegistry->getRepository(Voucher::class)->findBy(['userWon' => $user]);
        }

        return $this->render('front_office/home/index.html.twig', [
            'post' => $post,
            'markets' => $markets,
            'voucher' => $voucher,
            'categories' => CategoryEnum::cases(),
        ]);
    }
}
