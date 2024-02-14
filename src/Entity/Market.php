<?php

namespace App\Entity;

use App\Repository\MarketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarketRepository::class)]
class Market
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'marketRelated', targetEntity: Voucher::class)]
    private Collection $vouchers;

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
}
