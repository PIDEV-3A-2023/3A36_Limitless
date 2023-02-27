<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Il faut Ajouter un titre")]
    #[Assert\Length(min:5)]
    private ?string $titre = null;

    #[ORM\Column(length: 9999999)]
    #[Assert\NotBlank(message:"Il faut Ajouter un contenu")]
    #[Assert\Length(min:10)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_modification;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Commentaire::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $commentaires;

    #[ORM\Column(length: 255)]
    private ?string $imageBlog = null;

    #[ORM\Column]
    private ?int $etat = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: LikeBlog::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $likeBlogs;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: DislikeBlog::class,cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $dislikeBlogs;

    public function __construct()
    {
        $this->date_creation = new \DateTime();
        $this->date_modification = new \DateTime();
        $this->commentaires = new ArrayCollection();
        $this->likeBlogs = new ArrayCollection();
        $this->dislikeBlogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    /**
     * @return Collection<int, commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaire(commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getBlog() === $this) {
                $commentaire->setBlog(null);
            }
        }

        return $this;
    }

    public function getImageBlog(): ?string
    {
        return $this->imageBlog;
    }

    public function setImageBlog(string $imageBlog): self
    {
        $this->imageBlog = $imageBlog;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $likeBlog->setBlog($this);
        }

        return $this;
    }

    public function removeLikeBlog(LikeBlog $likeBlog): self
    {
        if ($this->likeBlogs->removeElement($likeBlog)) {
            // set the owning side to null (unless already changed)
            if ($likeBlog->getBlog() === $this) {
                $likeBlog->setBlog(null);
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
            $dislikeBlog->setBlog($this);
        }

        return $this;
    }

    public function removeDislikeBlog(DislikeBlog $dislikeBlog): self
    {
        if ($this->dislikeBlogs->removeElement($dislikeBlog)) {
            // set the owning side to null (unless already changed)
            if ($dislikeBlog->getBlog() === $this) {
                $dislikeBlog->setBlog(null);
            }
        }

        return $this;
    }

    public function getTotalLikes(): int
    {
        return count($this->likeBlogs);
    }

    public function getTotalDislikes(): int
    {
        return count($this->dislikeBlogs);
    }

    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likeBlogs as $likeBlogs) {
            if ($likeBlogs->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function isDislikedByUser(User $user): bool
    {
        foreach ($this->dislikeBlogs as $dislikeBlogs) {
            if ($dislikeBlogs->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function like(User $user): void
    {
        if (!$this->isLikedByUser($user)) {
            $like = new LikeBlog();
            $like->setBlog($this);
            $like->setUser($user);

            $this->likeBlogs[] = $like;
        }
    }

    public function dislike(User $user): void
    {
        if (!$this->isDislikedByUser($user)) {
            $dislike = new DislikeBlog();
            $dislike->setBlog($this);
            $dislike->setUser($user);

            $this->dislikeBlogs[] = $dislike;
        }
    }
}
