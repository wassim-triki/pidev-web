<?php

namespace App\Entity;

use App\Repository\PostcommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostcommentaireRepository::class)]
class Postcommentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'postcommentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostGroup $postgroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPostgroup(): ?PostGroup
    {
        return $this->postgroup;
    }

    public function setPostgroup(?PostGroup $postgroup): static
    {
        $this->postgroup = $postgroup;

        return $this;
    }
}
