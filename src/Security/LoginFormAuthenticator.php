<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Entity\User; // Import de l'entité User
use Doctrine\ORM\EntityManagerInterface; // Import du gestionnaire d'entités
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;



class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');


        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);


        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Incorrect email or password.');
        }
        if(!$user->isVerified() ){
            throw new CustomUserMessageAuthenticationException('Please verify your email.');
            // last username entered by the user
        }else{
            if(!$user->isEnabled())
                throw new CustomUserMessageAuthenticationException('This user is blocked.');
        }

        // Check if the password is correct
        $password = $request->request->get('password', '');
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new CustomUserMessageAuthenticationException('Incorrect email or password.');
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if($targetPath=$this->getTargetPath($request->getSession(),$firewallName)){
            return new RedirectResponse($targetPath);
        }

        $user=$token->getUser();

//        if(in_array('ROLE_ADMIN',$user->getRoles(),true)){
//            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
//        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);


        return new RedirectResponse($this->urlGenerator->generate('app_login'));


    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}