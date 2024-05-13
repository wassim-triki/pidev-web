<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer", name: "id")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'answers'), ORM\JoinColumn(name: "userId", nullable: true)]
    private ?User $user_id = null;

    #[ORM\OneToOne(targetEntity: Question::class, inversedBy: 'answer', cascade: ['persist', 'remove']), ORM\JoinColumn(name: "questionId", nullable: false)]
    private ?Question $question_id = null;

    #[ORM\Column(type: "string", length: 255, name: "body")]
    private ?string $body = null;

    #[ORM\Column(type: "datetime_immutable", name: "createdAt")]
    private ?\DateTimeImmutable $created_at = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getQuestionId(): ?Question
    {
        return $this->question_id;
    }

    public function setQuestionId(Question $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
