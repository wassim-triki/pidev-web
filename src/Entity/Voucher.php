<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;


#[ORM\Entity(repositoryClass: VoucherRepository::class)]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[CustomAssert\ValidDate]
    private ?\DateTimeImmutable $expiration = null;

    #[ORM\Column]
    #[CustomAssert\IntegerValue]
    #[Assert\NotBlank(message: "The name must not be blank")]
    private ?float $value = null;

    #[ORM\Column]
    #[Assert\EqualTo(value: 1, message: "Usage limit must be greater than 1")]
    #[Assert\Regex(pattern: "/^\d+$/", message: "Usage limit must be a digit")]
    private ?int $usageLimit = null;

    #[ORM\ManyToOne(inversedBy: 'vouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VoucherCategory $category = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'vouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userWon = null;

    #[ORM\ManyToOne(inversedBy: 'vouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Market $marketRelated = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    #[ORM\Column]
    private ?bool $isGivenToUser = null;

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

    public function getExpiration(): ?\DateTimeImmutable
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTimeImmutable $expiration): static
    {
        $this->expiration = $expiration;

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

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(int $usageLimit): static
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    public function getCategory(): ?VoucherCategory
    {
        return $this->category;
    }

    public function setCategory(?VoucherCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUserWon(): ?User
    {
        return $this->userWon;
    }

    public function setUserWon(?User $userWon): static
    {
        $this->userWon = $userWon;

        return $this;
    }

    public function getMarketRelated(): ?Market
    {
        return $this->marketRelated;
    }

    public function setMarketRelated(?Market $marketRelated): static
    {
        $this->marketRelated = $marketRelated;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): static
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function isIsGivenToUser(): ?bool
    {
        return $this->isGivenToUser;
    }

    public function setIsGivenToUser(bool $isGivenToUser): static
    {
        $this->isGivenToUser = $isGivenToUser;

        return $this;
    }
}
