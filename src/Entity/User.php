<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Blog::class)]
    private Collection $blogs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LikeBlog::class)]
    private Collection $likeBlogs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DislikeBlog::class)]
    private Collection $dislikeBlogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->likeBlogs = new ArrayCollection();
        $this->dislikeBlogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
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
            $blog->setUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getUser() === $this) {
                $blog->setUser(null);
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
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
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
            $report->setUser($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getUser() === $this) {
                $report->setUser(null);
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
            $likeBlog->setUser($this);
        }

        return $this;
    }

    public function removeLikeBlog(LikeBlog $likeBlog): self
    {
        if ($this->likeBlogs->removeElement($likeBlog)) {
            // set the owning side to null (unless already changed)
            if ($likeBlog->getUser() === $this) {
                $likeBlog->setUser(null);
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
            $dislikeBlog->setUser($this);
        }

        return $this;
    }

    public function removeDislikeBlog(DislikeBlog $dislikeBlog): self
    {
        if ($this->dislikeBlogs->removeElement($dislikeBlog)) {
            // set the owning side to null (unless already changed)
            if ($dislikeBlog->getUser() === $this) {
                $dislikeBlog->setUser(null);
            }
        }

        return $this;
    }
}
