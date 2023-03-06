<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
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

    /*#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at = null;*/
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Equipe::class)]
    private Collection $equipes;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Jaime::class)]
    private Collection $jaimes;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Jaimepas::class)]
    private Collection $jaimepas;

    #[ORM\OneToMany(mappedBy: 'Joueur', targetEntity: Blog::class)]
    private Collection $blogs;

    #[ORM\OneToMany(mappedBy: 'Joueur', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'Joueur', targetEntity: DislikeBlog::class)]
    private Collection $dislikeBlogs;

    #[ORM\OneToMany(mappedBy: 'Joueur', targetEntity: LikeBlog::class)]
    private Collection $likeBlogs;

    #[ORM\OneToMany(mappedBy: 'Joueur', targetEntity: Report::class)]
    private Collection $reports;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->blogs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->dislikeBlogs = new ArrayCollection();
        $this->likeBlogs = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->jaimes = new ArrayCollection();
        $this->jaimepas = new ArrayCollection();
        $this->equipes = new ArrayCollection();
    }

    


    


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

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }


    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setJoueur($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getJoueur() === $this) {
                $blog->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setJoueur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getJoueur() === $this) {
                $commentaire->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DislikeBlog>
     */
    public function getDislikeBlogs(): Collection
    {
        return $this->dislikeBlogs;
    }

    public function addDislikeBlog(DislikeBlog $dislikeBlog): self
    {
        if (!$this->dislikeBlogs->contains($dislikeBlog)) {
            $this->dislikeBlogs->add($dislikeBlog);
            $dislikeBlog->setJoueur($this);
        }

        return $this;
    }

    public function removeDislikeBlog(DislikeBlog $dislikeBlog): self
    {
        if ($this->dislikeBlogs->removeElement($dislikeBlog)) {
            // set the owning side to null (unless already changed)
            if ($dislikeBlog->getJoueur() === $this) {
                $dislikeBlog->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LikeBlog>
     */
    public function getLikeBlogs(): Collection
    {
        return $this->likeBlogs;
    }

    public function addLikeBlog(LikeBlog $likeBlog): self
    {
        if (!$this->likeBlogs->contains($likeBlog)) {
            $this->likeBlogs->add($likeBlog);
            $likeBlog->setJoueur($this);
        }

        return $this;
    }

    public function removeLikeBlog(LikeBlog $likeBlog): self
    {
        if ($this->likeBlogs->removeElement($likeBlog)) {
            // set the owning side to null (unless already changed)
            if ($likeBlog->getJoueur() === $this) {
                $likeBlog->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setJoueur($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getJoueur() === $this) {
                $report->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Jaime>
     */
    public function getJaimes(): Collection
    {
        return $this->jaimes;
    }

    public function addJaime(Jaime $jaime): self
    {
        if (!$this->jaimes->contains($jaime)) {
            $this->jaimes->add($jaime);
            $jaime->setJoueur($this);
        }

        return $this;
    }

    public function removeJaime(Jaime $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getJoueur() === $this) {
                $jaime->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Jaimepas>
     */
    public function getJaimepas(): Collection
    {
        return $this->jaimepas;
    }

    public function addJaimepa(Jaimepas $jaimepa): self
    {
        if (!$this->jaimepas->contains($jaimepa)) {
            $this->jaimepas->add($jaimepa);
            $jaimepa->setJoueur($this);
        }

        return $this;
    }

    public function removeJaimepa(Jaimepas $jaimepa): self
    {
        if ($this->jaimepas->removeElement($jaimepa)) {
            // set the owning side to null (unless already changed)
            if ($jaimepa->getJoueur() === $this) {
                $jaimepa->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes->add($equipe);
            $equipe->setJoueur($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getJoueur() === $this) {
                $equipe->setJoueur(null);
            }
        }

        return $this;
    }
}
