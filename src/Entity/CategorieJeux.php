<?php

namespace App\Entity;

use App\Repository\CategorieJeuxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieJeuxRepository::class)]
class CategorieJeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Nom Categorie est vide ")]
    private ?string $NomCat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCat(): ?string
    {
        return $this->NomCat;
    }

    public function setNomCat(string $NomCat): self
    {
        $this->NomCat = $NomCat;

        return $this;
    }

    public function __toString()
    {
        return $this->NomCat;
    }
}
