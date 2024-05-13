<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\GenderEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator\Constraints\Password;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Password is required", groups: ["registration"])]
    #[Password(groups: ["registration"])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(\\+216)?[2459]\\d{7}$/',
        message: "Phone number must start with 2, 5, 9, 4 or +216 and be exactly 8 digits long",
        groups: ["registration"],
    )]
    private ?string $phone = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isEnabled = true;

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: true)]
    private ?string $emailVerificationToken = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PostGroup::class)]
    private Collection $postGroups;

    public function getEmailVerificationToken(): ?string
    {
        return $this->emailVerificationToken;
    }

    public function setEmailVerificationToken(?string $emailVerificationToken): void
    {
        $this->emailVerificationToken = $emailVerificationToken;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Username is required")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "Username must be at least {{ limit }} characters long",
        maxMessage: "Username cannot be longer than {{ limit }} characters"
    )]
    private ?string $username;

    #[ORM\Column(length: 255, enumType: GenderEnum::class)]
    #[Assert\NotNull(message: "Please select a gender.")]
    private ?GenderEnum $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $user;


    #[ORM\Column]
    private ?int $avertissementsCount = 0;

    #[ORM\OneToMany(mappedBy: 'f', targetEntity: Avertissement::class)]
    private Collection $avertissements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Postcommentaire::class)]
    private Collection $postcommentaires;

    #[ORM\OneToMany(mappedBy: 'User_id', targetEntity: Question::class)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Answer::class)]
    private Collection $answers;

    public function __construct()
    {
        $this->avertissements = new ArrayCollection();
        $this->postGroups = new ArrayCollection();
        $this->postcommentaires = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->setAvertissementsCount(0);
        $this->vouchers = new ArrayCollection();
    }

    #[ORM\Column(nullable: true)]
    private ?int $reputation = null;

    #[ORM\OneToMany(mappedBy: 'userWon', targetEntity: Voucher::class, cascade: ['persist', 'remove'])]
    private Collection $vouchers;





    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone; // Correctly assigning to $this->phone

        return $this;
    }


    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }



    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(?GenderEnum $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Post $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(Post $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;
    }



    public function getAvertissementsCount(): ?int
    {
        return $this->avertissementsCount;
    }

    public function setAvertissementsCount(int $avertissementsCount): static
    {
        $this->avertissementsCount = $avertissementsCount;

        return $this;
    }

    /**
     * @return Collection<int, Avertissement>
     */
    public function getAvertissements(): Collection
    {
        return $this->avertissements;
    }

    public function addAvertissement(Avertissement $avertissement): static
    {
        if (!$this->avertissements->contains($avertissement)) {
            $this->avertissements->add($avertissement);
            $avertissement->setF($this);
        }

        return $this;
    }

    public function removeAvertissement(Avertissement $avertissement): static
    {
        if ($this->avertissements->removeElement($avertissement)) {
            // set the owning side to null (unless already changed)
            if ($avertissement->getF() === $this) {
                $avertissement->setF(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostGroup>
     */
    public function getPostGroups(): Collection
    {
        return $this->postGroups;
    }

    public function addPostGroup(PostGroup $postGroup): static
    {
        if (!$this->postGroups->contains($postGroup)) {
            $this->postGroups->add($postGroup);
            $postGroup->setUser($this);
        }

        return $this;
    }

    public function removePostGroup(PostGroup $postGroup): static
    {
        if ($this->postGroups->removeElement($postGroup)) {
            // set the owning side to null (unless already changed)
            if ($postGroup->getUser() === $this) {
                $postGroup->setUser(null);
            }
        }

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
            $postcommentaire->setUser($this);
        }

        return $this;
    }

    public function removePostcommentaire(Postcommentaire $postcommentaire): static
    {
        if ($this->postcommentaires->removeElement($postcommentaire)) {
            // set the owning side to null (unless already changed)
            if ($postcommentaire->getUser() === $this) {
                $postcommentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setUserId($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getUserId() === $this) {
                $question->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setUserId($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getUserId() === $this) {
                $answer->setUserId(null);
            }
        }

        return $this;
    }
    public function getReputation(): ?int
    {
        return $this->reputation;
    }

    public function setReputation(?int $reputation): static
    {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * @return Collection<int, Voucher>
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): static
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers->add($voucher);
            $voucher->setUserWon($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): static
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getUserWon() === $this) {
                $voucher->setUserWon(null);
            }
        }

        return $this;
    }
}
