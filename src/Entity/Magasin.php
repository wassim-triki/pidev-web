<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagasinRepository::class)]
class Magasin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $magasinName = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: Vocher::class)]
    private Collection $vochers;

    public function __construct()
    {
        $this->vochers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMagasinName(): ?string
    {
        return $this->magasinName;
    }

    public function setMagasinName(string $magasinName): static
    {
        $this->magasinName = $magasinName;

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
     * @return Collection<int, Vocher>
     */
    public function getVochers(): Collection
    {
        return $this->vochers;
    }

    public function addVocher(Vocher $vocher): static
    {
        if (!$this->vochers->contains($vocher)) {
            $this->vochers->add($vocher);
            $vocher->setBoutique($this);
        }

        return $this;
    }

    public function removeVocher(Vocher $vocher): static
    {
        if ($this->vochers->removeElement($vocher)) {
            // set the owning side to null (unless already changed)
            if ($vocher->getBoutique() === $this) {
                $vocher->setBoutique(null);
            }
        }

        return $this;
    }
}
