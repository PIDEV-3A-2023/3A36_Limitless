<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomEquipe = null;

    #[ORM\OneToMany(mappedBy: 'equipe1', targetEntity: matches::class)]
    private Collection $nomEquipe1;

    #[ORM\OneToMany(mappedBy: 'equipe2', targetEntity: matches::class)]
    private Collection $nomEquipe2;

    public function __construct()
    {
        $this->nomEquipe1 = new ArrayCollection();
        $this->nomEquipe2 = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nomEquipe;  
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(?string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    /**
     * @return Collection<int, matches>
     */
    public function getNomEquipe1(): Collection
    {
        return $this->nomEquipe1;
    }

    public function addNomEquipe1(matches $nomEquipe1): self
    {
        if (!$this->nomEquipe1->contains($nomEquipe1)) {
            $this->nomEquipe1->add($nomEquipe1);
            $nomEquipe1->setEquipe1($this);
        }

        return $this;
    }

    public function removeNomEquipe1(matches $nomEquipe1): self
    {
        if ($this->nomEquipe1->removeElement($nomEquipe1)) {
            // set the owning side to null (unless already changed)
            if ($nomEquipe1->getEquipe1() === $this) {
                $nomEquipe1->setEquipe1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, matches>
     */
    public function getNomEquipe2(): Collection
    {
        return $this->nomEquipe2;
    }

    public function addNomEquipe2(matches $nomEquipe2): self
    {
        if (!$this->nomEquipe2->contains($nomEquipe2)) {
            $this->nomEquipe2->add($nomEquipe2);
            $nomEquipe2->setEquipe2($this);
        }

        return $this;
    }

    public function removeNomEquipe2(matches $nomEquipe2): self
    {
        if ($this->nomEquipe2->removeElement($nomEquipe2)) {
            // set the owning side to null (unless already changed)
            if ($nomEquipe2->getEquipe2() === $this) {
                $nomEquipe2->setEquipe2(null);
            }
        }

        return $this;
    }
}
