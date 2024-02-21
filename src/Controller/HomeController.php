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
class HomeController extends AbstractController
{
    private $managerRegistry;
    private SessionInterface $session;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, ManagerRegistry $managerRegistry, SessionInterface $session)
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // hedha kollou bech yetna7aa !
        $user = $this->fetchUserFromDatabase();
        $this->storeUserInSession($user);
        $userEmail = $this->session->get('user_email');
        $userRoles = $this->session->get('user_roles');
        $this->authenticateUser($user);
        // youfa hnee
        $markets = $this->managerRegistry->getRepository(Market::class)->findAll();
        $voucher = $this->managerRegistry->getRepository(Voucher::class)->findBy(['userWon' => $user]);
        
        return $this->render('home/index.html.twig', [
            'markets' => $markets,
            'voucher' => $voucher,
        ]);
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

    // hedha kollou pour le test
    private function fetchUserFromDatabase(): ?User
    {
        // You can customize this method to fetch a user based on your application's logic
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
