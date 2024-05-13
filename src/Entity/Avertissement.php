<?php

namespace App\Entity;

use App\Repository\AvertissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Reclamation;

#[ORM\Entity(repositoryClass: AvertissementRepository::class)]
class Avertissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ReportedUsername = null;

  

    #[ORM\OneToMany(mappedBy: 's', targetEntity: Reclamation::class, cascade: ["remove"])]
    private Collection $reclamations;

    #[ORM\Column(nullable: true)]
    private ?bool $confirmation = null;

    #[ORM\ManyToOne(inversedBy: 'avertissements')]
    private ?User $f = null;

    #[ORM\Column(length: 255)]
    private ?string $screenShot = null;

    #[ORM\Column(length: 255)]
    private ?string $raison = null;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportedUsername(): ?string
    {
        return $this->ReportedUsername;
    }

    public function setReportedUsername(?string $ReportedUsername): static
    {
        $this->ReportedUsername = $ReportedUsername;

        return $this;
    }

  

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setS($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getS() === $this) {
                $reclamation->setS(null);
            }
        }

        return $this;
    }

    public function isConfirmation(): ?bool
    {
        return $this->confirmation;
    }

    public function setConfirmation(bool $confirmation): static
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function getF(): ?User
    {
        return $this->f;
    }

    public function setF(?User $f): static
    {
        $this->f = $f;

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

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

   

  


 

  

  

   
   

   

    

    


    

   
}
