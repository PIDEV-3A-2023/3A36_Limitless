<?php

namespace App\Entity;

use App\Repository\CategorieJeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

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

    #[ORM\ManyToMany(targetEntity: CategorieJeux::class)]
    private Collection $Jeux;

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
    public function __construct()
    {
        $this->Jeux = new ArrayCollection();
    }

    public function countJeux(): int
    {
        return $this->Jeux->count();
    }
    public function getNumberOfJeuxByCategorie(): array
    {
        $numberOfJeuxByCategorie = [];

        foreach ($this->Jeux as $jeu) {
            foreach ($jeu->getCategories() as $categorie) {
                if (!array_key_exists($categorie->getNomCat(), $numberOfJeuxByCategorie)) {
                    $numberOfJeuxByCategorie[$categorie->getNomCat()] = 0;
                }
                $numberOfJeuxByCategorie[$categorie->getNomCat()]++;
            }
        }

        return $numberOfJeuxByCategorie;
    }
}
