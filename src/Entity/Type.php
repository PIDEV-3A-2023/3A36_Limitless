<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typejeu = null;

    #[ORM\ManyToMany(targetEntity: Jeu::class, mappedBy: 'type')]
    private Collection $jeus;

    public function __construct()
    {
        $this->jeus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypejeu(): ?string
    {
        return $this->typejeu;
    }

    public function setTypejeu(?string $typejeu): self
    {
        $this->typejeu = $typejeu;

        return $this;
    }

    /**
     * @return Collection<int, Jeu>
     */
    public function getJeus(): Collection
    {
        return $this->jeus;
    }

    public function addJeu(Jeu $jeu): self
    {
        if (!$this->jeus->contains($jeu)) {
            $this->jeus->add($jeu);
            $jeu->addType($this);
        }

        return $this;
    }

    public function removeJeu(Jeu $jeu): self
    {
        if ($this->jeus->removeElement($jeu)) {
            $jeu->removeType($this);
        }

        return $this;
    }
}
