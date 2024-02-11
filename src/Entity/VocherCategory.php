<?php

namespace App\Entity;

use App\Repository\VocherCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VocherCategoryRepository::class)]
class VocherCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $discription = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Vocher::class)]
    private Collection $vochers;

    public function __construct()
    {
        $this->vochers = new ArrayCollection();
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
            $vocher->setCategory($this);
        }

        return $this;
    }

    public function removeVocher(Vocher $vocher): static
    {
        if ($this->vochers->removeElement($vocher)) {
            // set the owning side to null (unless already changed)
            if ($vocher->getCategory() === $this) {
                $vocher->setCategory(null);
            }
        }

        return $this;
    }
}
