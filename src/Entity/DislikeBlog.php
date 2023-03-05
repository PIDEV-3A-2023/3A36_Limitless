<?php

namespace App\Entity;

use App\Repository\DislikeBlogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DislikeBlogRepository::class)]
class DislikeBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dislikeBlogs')]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(inversedBy: 'dislikeBlogs')]
    private ?Joueur $Joueur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->Joueur;
    }

    public function setJoueur(?Joueur $Joueur): self
    {
        $this->Joueur = $Joueur;

        return $this;
    }
}
