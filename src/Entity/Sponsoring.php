<?php

namespace App\Entity;

use App\Repository\SponsoringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponsoringRepository::class)]
class Sponsoring
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "PLEASE! Enter name ")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "The date n'est pas vide")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "PLEASE! check duration of contrat")]
    private ?string $contrat = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "PLEASE! the description")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'sponsoring', targetEntity: PostGroup::class)]
    private Collection $postgroup;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "PLEASE! check etat")]
    private ?string $type = null;

    public function __construct()
    {
        $this->postgroup = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(string $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, PostGroup>
     */
    public function getPostgroup(): Collection
    {
        return $this->postgroup;
    }

    public function addPostgroup(PostGroup $postgroup): static
    {
        if (!$this->postgroup->contains($postgroup)) {
            $this->postgroup->add($postgroup);
            $postgroup->setSponsoring($this);
        }

        return $this;
    }

    public function removePostgroup(PostGroup $postgroup): static
    {
        if ($this->postgroup->removeElement($postgroup)) {
            // set the owning side to null (unless already changed)
            if ($postgroup->getSponsoring() === $this) {
                $postgroup->setSponsoring(null);
            }
        }

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
   
}
