<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Index;
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: JoueurRepository::class)]
//#[ORM\Table(name: 'joueur', indexes: [new Index(columns: ['nom', 'prenom', 'email', 'ign'], flags: ['fulltext'])])]
#[ORM\Index(name: 'joueur', columns: ['nom', 'prenom', 'email', 'ign'], flags: ['fulltext'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse e-mail')]
#[UniqueEntity(fields: ['ign'], message: 'Il y a déjà un compte avec cette IGN')]
class Joueur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de joueur ne peut pas dépasser {{ limit }} lettres."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z ]+$/",
        message: "Le nom de joueur ne peut être qu'alphabétique."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le prenom de joueur ne peut pas dépasser {{ limit }} lettres."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z ]+$/",
        message: "Le prenom de joueur ne peut être qu'alphabétique."
    )]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\LessThan(
        '-16 years',
        message: "Vous devez avoir au moins 16 ans pour vous inscrire."
    )]
    private ?\DateTimeInterface $datenai = null;

    #[ORM\Column(length: 255)]
    private ?string $pprofile = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_banned = false;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 12,
        maxMessage: "L'IGN ne peut pas dépasser {{ limit }} lettres."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9]+$/",
        message: "L'IGN ne peut être qu'alphanumérique."
    )]
    private ?string $ign = null;

    #[ORM\Column(nullable: true)]
    //cannot be negative
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: "Le nombre de victoires ne peut pas être négatif."
    )]
    private ?int $wins = 0;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: "Le nombre de defaites ne peut pas être négatif."
    )]
    private ?int $loses = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at = null;

    


    


    public function getId(): ?string
    {
        //return $this->id;
        return (string) $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
        return (string) $this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenai(): ?\DateTimeInterface
    {
        return $this->datenai;
    }

    public function setDatenai(\DateTimeInterface $datenai): self
    {
        $this->datenai = $datenai;

        return $this;
    }

    public function getPprofile(): ?string
    {
        return $this->pprofile;
    }

    public function setPprofile(string $pprofile): self
    {
        $this->pprofile = $pprofile;

        return $this;
    }

    public function isIsBanned(): ?bool
    {
        return $this->is_banned;
    }

    public function setIsBanned(?bool $is_banned): self
    {
        $this->is_banned = $is_banned;

        return $this;
    }

    public function getIgn(): ?string
    {
        return $this->ign;
    }

    public function setIgn(string $ign): self
    {
        $this->ign = $ign;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(?int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLoses(): ?int
    {
        return $this->loses;
    }

    public function setLoses(?int $loses): self
    {
        $this->loses = $loses;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
    

    #[ORM\PrePersist]
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    
}
