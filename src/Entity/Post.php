<?php

namespace App\Entity;

use App\Enum\CategoryEnum;
use App\Enum\PostTypeEnum;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "The titre n'est pas vide")]
    #[Assert\Length(min: 3, minMessage: "le titre doit avoir un longeur plus que 5 charactére")]
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[Assert\NotBlank(message: "The description n'est pas vide")]
    #[Assert\Length(min: 10, minMessage: "le description doit avoir un longeur plus que 20 charactére")]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank(message: "The date n'est pas vide")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: "The type n'est pas vide")]
    #[ORM\Column(length: 255, enumType: PostTypeEnum::class)]
    private ?PostTypeEnum $type = null;

    // #[Assert\NotBlank(message: "The image n'est pas vide")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[Assert\NotBlank(message: "The place n'est pas vide")]
    #[Assert\Length(min: 5, minMessage: "le place doit avoir un longeur plus que 5 charactére")]
    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(length: 255, enumType: CategoryEnum::class)]
    #[Assert\NotNull(message: "Please select a category.")]
    private ?CategoryEnum $category = null;


    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?User $user = null;


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getType(): ?string
    {
        return $this->type?->value;
    }

    public function setType(string $type): self
    {
        $this->type = PostTypeEnum::from($type);

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

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

    public function getCategory(): ?CategoryEnum
    {
        return $this->category;
    }

    public function setCategory(CategoryEnum $category): self
    {
        $this->category = $category;

        return $this;
    }
}
