<?php

namespace App\Service;

use App\Repository\PostRepository;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\ValidAt;

use DateTimeZone;


class JwtTokenService
{
    private $config;
    private $postRepository;


    public function __construct(PostRepository $postRepository)
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText("al9ani"));

        // Add validation constraints
        $this->config->setValidationConstraints(
            new SignedWith($this->config->signer(), $this->config->signingKey()),
            new ValidAt(new SystemClock(new DateTimeZone(date_default_timezone_get())))
        );
        $this->postRepository = $postRepository;
    }



    public function createToken(array $claims, \DateInterval $expiration): string
    {
        $now = new \DateTimeImmutable();

        return $this->config->builder()
            ->issuedAt($now)
            ->expiresAt($now->add($expiration))
            ->withClaim('user_id', $claims['user_id'])
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    public function validateToken(string $token): bool
    {
        $parsedToken = $this->config->parser()->parse($token);

        return $this->config->validator()->validate($parsedToken, ...$this->config->validationConstraints());
    }

    public function getPostStatistics()
    {
        $totalPosts = $this->postRepository->count([]);
        $lostPosts = $this->postRepository->count(['type' => 'Lost']);
        $foundPosts = $this->postRepository->count(['type' => 'Found']);

        return [
            'totalPosts' => $totalPosts,
            'lostPosts' => $lostPosts,
            'foundPosts' => $foundPosts,
        ];
    }
}
