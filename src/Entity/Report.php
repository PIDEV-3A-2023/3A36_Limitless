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
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?commentaire $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
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
}
