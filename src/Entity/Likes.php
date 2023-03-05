<?php

namespace App\Entity;
use App\Entity\Joueur;
use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesRepository::class)]
class Likes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $type = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Joueur $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getUser(): ?Joueur
    {
        return $this->user;
    }

    public function setUser(?Joueur $user): self
    {
        $this->user = $user;

        return $this;
    }
}
