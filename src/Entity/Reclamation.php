<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TypeReclamationEnum;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Subject ies required")]
    #[Assert\Length(min:8,minMessage:'subject must be at least 8 characters long.')]
    private ?string $subject = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Description is required")]
    private ?string $description = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private ?string $EmailReportedUser = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "select one items")]
    private ?string $TypeReclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Avertissement $s = null;

    #[ORM\Column(length: 255)]
    private ?string $screenShot = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmailReportedUser(): ?string
    {
        return $this->EmailReportedUser;
    }

    public function setEmailReportedUser(?string $EmailReportedUser): static
    {
        $this->EmailReportedUser = $EmailReportedUser;

        return $this;
    }
    public function getTypeReclamation(): ?string
{
    return $this->TypeReclamation;
}

public function setTypeReclamation(?string $TypeReclamation): static
{
    $this->TypeReclamation = $TypeReclamation;

    return $this;
}


    public function getS(): ?Avertissement
    {
        return $this->s;
    }

    public function setS(?Avertissement $s): static
    {
        $this->s = $s;

        return $this;
    }

    public function getScreenShot(): ?string
    {
        return $this->screenShot;
    }

    public function setScreenShot(string $screenShot): static
    {
        $this->screenShot = $screenShot;

        return $this;
    }

}