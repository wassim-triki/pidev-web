<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "id")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions'), ORM\JoinColumn(name: "userId", nullable: false)]
    private ?User $User_id = null;
    
    #[Assert\NotBlank(message: "Le titre ne doit pas etre vide!"), Assert\Length(min: 5, minMessage: "le titre doit avoir un longeur plus que 5 charactères!"), ORM\Column(type: "string", length: 255, name: "title")]
    private ?string $title = null;
    
    #[Assert\NotBlank(message: "Implementez votre question"), Assert\Length(min: 10, minMessage: "votre question doit avoir un longueur plus de 10 charactères!"), ORM\Column(type: "string", length: 255, name: "body")]
    private ?string $body = null;

    #[ORM\Column(type: "datetime_immutable", name: "createdAt")]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToOne(targetEntity: Answer::class, mappedBy: 'question_id', cascade: ['persist', 'remove'])]
    private ?Answer $answer = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->User_id;
    }

    public function setUserId(?User $User_id): static
    {
        $this->User_id = $User_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): static
    {
        // set the owning side of the relation if necessary
        if ($answer->getQuestionId() !== $this) {
            $answer->setQuestionId($this);
        }

        $this->answer = $answer;

        return $this;
    }
}
