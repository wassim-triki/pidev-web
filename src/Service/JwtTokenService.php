<?php

namespace App\Service;

use App\Repository\SponsoringRepository as RepositorySponsoringRepository;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use src\Repository\SponsoringRepository;

use DateTimeZone;


class JwtTokenService
{
    private $config;
    private $SponsoringRepository;

    public function __construct(RepositorySponsoringRepository $SponsoringRepository)
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText("al9ani"));

        // Add validation constraints
        $this->config->setValidationConstraints(
            new SignedWith($this->config->signer(), $this->config->signingKey()),
            new ValidAt(new SystemClock(new DateTimeZone(date_default_timezone_get())))
        );
        $this->SponsoringRepository = $SponsoringRepository;
    }

    public function getSponsorStatistics()
    {
        $totalSponsor = $this->SponsoringRepository->count([]);
        $activeSponsor = $this->SponsoringRepository->count(['type' => 'ACTIVE']);
        $desactiveSponsor = $this->SponsoringRepository->count(['type' => 'DESACTIVE']);

        return [
            'totalSponsor' => $totalSponsor,
            'activeSponsor' => $activeSponsor,
            'desactiveSponsor' => $desactiveSponsor,
        ];
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
}
