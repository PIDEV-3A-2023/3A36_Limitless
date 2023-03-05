<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?commentaire $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?Joueur $Joueur = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCommentaire(): ?commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;

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
