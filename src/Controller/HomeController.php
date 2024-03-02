<?php

namespace App\Controller;

use App\Entity\Market;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MarketRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\Voucher;
use Doctrine\ORM\EntityManagerInterface;
class HomeController extends AbstractController
{
    private $managerRegistry;
    private SessionInterface $session;
    private TokenStorageInterface $tokenStorage;
    private EntityManagerInterface $entityManager;


    public function __construct(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $userHaveSession = $this->session->get('user_email');
        // hedha kollou bech yetna7aa !
        $user = $this->fetchUserFromDatabase();
        $this->storeUserInSession($user);
        $this->authenticateUser($user);
        // youfa hnee
        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        $voucher = $this->managerRegistry->getRepository(Voucher::class)->findBy(['userWon' => $user]);
        
        return $this->render('home/index.html.twig', [
            'markets' => $markets,
            'voucher' => $voucher,
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function profile() : Response {
        $user = $this->fetchUserFromDatabase();
        $this->storeUserInSession($user);
        $this->authenticateUser($user);
        if($user){
            $vouchers = $this->entityManager->createQueryBuilder()
            ->select('v')
            ->from(Voucher::class, 'v')
            ->where('v.userWon = :userId')
            ->andWhere('v.isValid = :isValid')
            ->setParameter('userId', $user->getId())
            ->setParameter('isValid', true)
            ->getQuery()
            ->getResult();
    
            
            $voucherCount = count($vouchers);
            return $this->render('frontOffice/profile.html.twig', [
                'voucherCount' => $voucherCount,
                'vouchers' => $vouchers,
            ]);
        }
        else {
            return $this->redirectToRoute('app_loggin');
        }
        
    }

    #[Route('/search', name: 'search_market')]
    public function search(Request $request, MarketRepository $marketRepository): Response
    {
        $name = $request->query->get('name');
        $region = $request->query->get('region');

        $markets = $marketRepository->findBySearchCriteria($name, $region);

        return $this->render('market/search_results.html.twig', [
            'markets' => $markets,
        ]);
    }

    #[Route('/loggin', name: 'app_loggin')]
    public function loggin(): Response
    {
        return $this->render('frontOffice/loggin.html.twig');
    }

    // hedha kollou pour le test
    private function fetchUserFromDatabase(): ?User
    {
        return $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => 'user1@gmail.com']);
    }

    private function storeUserInSession(?User $user): void
    {
        if ($user) {
            $this->session->set('user_email', $user->getEmail());
            $this->session->set('user_roles', $user->getRoles());
        }
    }

    private function authenticateUser(User $user): void
    {
        $token = new UsernamePasswordToken($user->getEmail(), null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));
    }
    // youfa hne
}
