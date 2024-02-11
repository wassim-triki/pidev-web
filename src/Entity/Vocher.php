<?php

namespace App\Entity;

use App\Repository\VocherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VocherRepository::class)]
class Vocher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $expirationDate = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\Column]
    private ?int $usageLimite = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'vochers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?magasin $boutique = null;

    #[ORM\ManyToOne(inversedBy: 'vochers')]
    private ?user $userWon = null;

    #[ORM\ManyToOne(inversedBy: 'vochers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VocherCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeImmutable $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getUsageLimite(): ?int
    {
        return $this->usageLimite;
    }

    public function setUsageLimite(int $usageLimite): static
    {
        $this->usageLimite = $usageLimite;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBoutique(): ?magasin
    {
        return $this->boutique;
    }

    public function setBoutique(?magasin $boutique): static
    {
        $this->boutique = $boutique;

        return $this;
    }

    public function getUserWon(): ?user
    {
        return $this->userWon;
    }

    public function setUserWon(?user $userWon): static
    {
        $this->userWon = $userWon;

        return $this;
    }

    public function getCategory(): ?VocherCategory
    {
        return $this->category;
    }

    public function setCategory(?VocherCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
