<?php

namespace App\Entity;

use App\Repository\VoucherCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoucherCategoryRepository::class)]
class VoucherCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Description cannot be blank')]
    #[Assert\Choice(choices: ['purchase', 'discount'], message: 'Invalid category')]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description cannot be blank')]
    private ?string $discription = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Voucher::class, cascade: ['persist', 'remove'])]
    private Collection $vouchers;

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(string $discription): static
    {
        $this->discription = $discription;

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
            $voucher->setCategory($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): static
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getCategory() === $this) {
                $voucher->setCategory(null);
            }
        }

        return $this;
    }
}
