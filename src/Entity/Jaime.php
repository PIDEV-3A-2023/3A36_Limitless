<?php

namespace App\Entity;

use App\Repository\JaimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JaimeRepository::class)]
class Jaime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?Equipe $equipe = null;

    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?Joueur $joueur = null;

    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

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
}
