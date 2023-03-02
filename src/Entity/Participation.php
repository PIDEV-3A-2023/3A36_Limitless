<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?string
    {
        return $this->participant;
    }

    public function setParticipant(?string $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
