<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Jaime::class)]
    private Collection $jaimes;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Jaimepas::class)]
    private Collection $jaimepas;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Equipe::class)]
    private Collection $equipes;

    public function __construct()
    {
        $this->jaimes = new ArrayCollection();
        $this->jaimepas = new ArrayCollection();
        $this->equipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Jaime>
     */
    public function getJaimes(): Collection
    {
        return $this->jaimes;
    }

    public function addJaime(Jaime $jaime): self
    {
        if (!$this->jaimes->contains($jaime)) {
            $this->jaimes->add($jaime);
            $jaime->setJoueur($this);
        }

        return $this;
    }

    public function removeJaime(Jaime $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getJoueur() === $this) {
                $jaime->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Jaimepas>
     */
    public function getJaimepas(): Collection
    {
        return $this->jaimepas;
    }

    public function addJaimepa(Jaimepas $jaimepa): self
    {
        if (!$this->jaimepas->contains($jaimepa)) {
            $this->jaimepas->add($jaimepa);
            $jaimepa->setJoueur($this);
        }

        return $this;
    }

    public function removeJaimepa(Jaimepas $jaimepa): self
    {
        if ($this->jaimepas->removeElement($jaimepa)) {
            // set the owning side to null (unless already changed)
            if ($jaimepa->getJoueur() === $this) {
                $jaimepa->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes->add($equipe);
            $equipe->setJoueur($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getJoueur() === $this) {
                $equipe->setJoueur(null);
            }
        }

        return $this;
    }
}
