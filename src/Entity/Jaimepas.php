<?php

namespace App\Entity;

use App\Repository\JaimepasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JaimepasRepository::class)]
class Jaimepas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jaimepas')]
    private ?Equipe $equipe = null;

   

    #[ORM\ManyToOne(inversedBy: 'jaimepas')]
    private ?Joueur $user = null;

    

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