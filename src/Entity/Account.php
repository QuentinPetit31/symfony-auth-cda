<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Le mail doit être non vide")]
    #[Assert\Email(message: "L'email {{ value }} n'est pas valide.")]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex(
        pattern: "/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}/",
        message: "Le mot de passe doit contenir lettres min, maj, nombre et 8 caractères"
    )]
    #[Assert\NotCompromisedPassword(message: "Le mot de passe est compromis")]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le prénom doit être non vide")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le prénom doit faire au minimum {{ limit }} caractères"
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom doit être non vide")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom doit faire au minimum {{ limit }} caractères"
    )]
    private ?string $lastname = null;

    #[ORM\Column(type: 'boolean')]
    private bool $status = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Efface les données sensibles temporaires si besoin
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;
        return $this;
    }
}
