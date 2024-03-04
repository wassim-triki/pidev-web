<?php

namespace App\Entity;

use App\Repository\PostcommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostcommentaireRepository::class)]
class Postcommentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "le commentaire n'est pas vide")]
    #[Assert\Length(min: 2, minMessage: "le commentaire doit avoir un longeur plus que 2 charactéres")]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $likes = 0; // Add this property to track the number of likes

        
    #[ORM\Column(type: "array")]
    private array $likedBy = [];


    #[ORM\ManyToOne(inversedBy: 'postcommentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostGroup $postgroup = null;

    #[ORM\ManyToOne(inversedBy: 'postcommentaires')]
    private ?User $user = null;

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

    public function getLikes(): int // Add this method to retrieve the number of likes
    {
        return $this->likes;
    }

    public function setLikes(int $likes): static // Add this method to set the number of likes
    {
        $this->likes = $likes;

        return $this;
    }

    public function incrementLikes(): void // Add this method to increment the number of likes
    {
        $this->likes++;
    }

    public function decrementLikes(): void // Add this method to decrement the number of likes
    {
        if ($this->likes > 0) {
            $this->likes--;
        }
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function getLikedBy(): ?array
    {
        return $this->likedBy;
    }

    public function setLikedBy(array $likedBy): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }
    public function isLikedByUser(User $user): bool
    {
        return in_array($user->getUserIdentifier(), $this->likedBy);
    }
}
