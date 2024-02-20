<?php

namespace App\Entity;

use App\Repository\PostGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[ORM\Entity(repositoryClass: PostGroupRepository::class)]
class PostGroup extends AbstractController
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

    #[ORM\ManyToOne(inversedBy: 'postgroup')]
    private ?Sponsoring $sponsoring = null;

    #[ORM\ManyToOne(inversedBy: 'postGroups')]
    private ?User $user = null;

    

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

    public function getSponsoring(): ?Sponsoring
    {
        return $this->sponsoring;
    }

    public function setSponsoring(?Sponsoring $sponsoring): static
    {
        $this->sponsoring = $sponsoring;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

   
}
