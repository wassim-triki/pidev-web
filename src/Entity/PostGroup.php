<?php

namespace App\Entity;

use App\Repository\PostGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostGroupRepository::class)]
class PostGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'postgroup', targetEntity: Postcommentaire::class, orphanRemoval: true)]
    private Collection $postcommentaires;

    

    public function __construct()
    {
        $this->postcommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Postcommentaire>
     */
    public function getPostcommentaires(): Collection
    {
        return $this->postcommentaires;
    }

    public function addPostcommentaire(Postcommentaire $postcommentaire): static
    {
        if (!$this->postcommentaires->contains($postcommentaire)) {
            $this->postcommentaires->add($postcommentaire);
            $postcommentaire->setPostgroup($this);
        }

        return $this;
    }

    public function removePostcommentaire(Postcommentaire $postcommentaire): static
    {
        if ($this->postcommentaires->removeElement($postcommentaire)) {
            // set the owning side to null (unless already changed)
            if ($postcommentaire->getPostgroup() === $this) {
                $postcommentaire->setPostgroup(null);
            }
        }

        return $this;
    }

   
}
