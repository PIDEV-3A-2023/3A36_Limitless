<?php

namespace App\Entity;

use App\Repository\TypeJeuxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeJeuxRepository::class)]
class TypeJeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->NomType;
    }

    public function setNomType(string $NomType): self
    {
        $this->NomType = $NomType;

        return $this;
    }
    public function __toString()
    {
        return $this->NomType;
    }
}
