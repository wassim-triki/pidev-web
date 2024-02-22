<?php

namespace App\Entity;

use App\Repository\MarketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MarketRepository::class)]
class Market
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "The name must not be blank")]
    #[Assert\Length(min:2,minMessage:"the market name must be at least 2 characters long")]
    #[ORM\Column(length: 50)]
    private ?string $name = null;   
    
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'marketRelated', targetEntity: Voucher::class)]
    private Collection $vouchers;

    #[Assert\NotBlank(message: "The city must not be blank")]
    #[Assert\Regex(
        pattern: '/^[A-Za-z\s]+$/',
        message: "The city name must contain only letters and spaces"
    )]
    #[ORM\Column(length: 60)]
    private ?string $region = null;

    #[Assert\NotBlank(message: "The city must not be blank")]
    #[Assert\Regex(
        pattern: '/^[A-Za-z\s]+$/',
        message: "The city name must contain only letters and spaces"
    )]
    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[Assert\NotBlank(message: "The zip code must not be blank")]
    #[Assert\Regex(
        pattern: '/^\d{4}$/',
        message: "The zip code must be a 4-digit number"
    )]
    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Voucher>
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): static
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers->add($voucher);
            $voucher->setMarketRelated($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): static
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getMarketRelated() === $this) {
                $voucher->setMarketRelated(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
