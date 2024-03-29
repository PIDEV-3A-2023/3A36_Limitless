<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[UniqueEntity(fields: ['nom_equipe'], message: 'nom d equipe déja utilisé')]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)] 
    #[Assert\NotBlank(message:"veuillez choisir un nom d equipe")]
    private ?string $nom_equipe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"veuillez choisir une description d'equipe")]
    private ?string $description_equipe = null;

    #[ORM\Column]
     /**
     * @ORM\Column(type="integer")
     
     * @Assert\NotBlank(message="Le nombre de joueurs est obligatoire")
     */
    private ?int $nb_joueurs = null;

    #[ORM\Column(length: 255)]
    private ?string $logo_equipe = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank(message:"veuillez choisir un site web")]
    private ?string $site_web = null;

    #[ORM\OneToMany(mappedBy: 'id_equipe', targetEntity: Sponsor::class, cascade: ["remove"], orphanRemoval: true)]
    private Collection $sponsors;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
     /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    private ?Joueur $joueur = null; 

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Jaime::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $jaimes;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Jaimepas::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $jaimepas;

    #[ORM\OneToMany(mappedBy: 'equipe1', targetEntity: Matches::class,cascade:['remove'],orphanRemoval: true)]
    private Collection $nomEquipe1;

    #[ORM\OneToMany(mappedBy: 'equipe2', targetEntity: Matches::class,cascade:['remove'],orphanRemoval: true)]
    private Collection $nomEquipe2;

    public function __construct()
    {
        $this->sponsors = new ArrayCollection();
        $this->jaimes = new ArrayCollection();
        $this->jaimepas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(string $nom_equipe): self
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getDescriptionEquipe(): ?string
    {
        return $this->description_equipe;
    }

    public function setDescriptionEquipe(string $description_equipe): self
    {
        $this->description_equipe = $description_equipe;

        return $this;
    }

    public function getNbJoueurs(): ?int
    {
        return $this->nb_joueurs;
    }

    public function setNbJoueurs(int $nb_joueurs): self
    {
        $this->nb_joueurs = $nb_joueurs;

        return $this;
    }

    public function getLogoEquipe(): ?string
    {
        return $this->logo_equipe;
    }

    public function setLogoEquipe(string $logo_equipe): self
    {
        $this->logo_equipe = $logo_equipe;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->site_web;
    }

    public function setSiteWeb(string $site_web): self
    {
        $this->site_web = $site_web;

        return $this;
    }

    public function __toString()
    {
        return $this->nom_equipe;
    }

    /**
     * @return Collection<int, Sponsor>
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsor $sponsor): self
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors->add($sponsor);
            $sponsor->setIdEquipe($this);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): self
    {
        if ($this->sponsors->removeElement($sponsor)) {
            // set the owning side to null (unless already changed)
            if ($sponsor->getIdEquipe() === $this) {
                $sponsor->setIdEquipe(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }
/**
     * @ORM\PrePersist()
     */
    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
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
            $jaime->setEquipe($this);
        }

        return $this;
    }

    public function removeJaime(Jaime $jaime): self
    {
        if ($this->jaimes->removeElement($jaime)) {
            // set the owning side to null (unless already changed)
            if ($jaime->getEquipe() === $this) {
                $jaime->setEquipe(null);
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

    public function addJaimepa(Jaimepas $jaimepas): self
    {
        if (!$this->jaimepas->contains($jaimepas)) {
            $this->jaimepas->add($jaimepas);
            $jaimepas->setEquipe($this);
        }

        return $this;
    }

    public function removeJaimepa(Jaimepas $jaimepas): self
    {
        if ($this->jaimepas->removeElement($jaimepas)) {
            // set the owning side to null (unless already changed)
            if ($jaimepas->getEquipe() === $this) {
                $jaimepas->setEquipe(null);
            }
        }

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getTotalJaimes(): int
    {
        return count($this->jaimes);
    }
 
    public function getTotaljaimepas(): int
    {
        return count($this->jaimepas);
    }

    public function isLikedByJoueur(Joueur $joueur): bool
    {
        foreach ($this->jaimes as $jaimes) {
            if ($jaimes->getJoueur() === $joueur) {
                return true;
            }
        }

        return false;
    }

    public function isDislikedByJoueur(Joueur $joueur): bool
    {
        foreach ($this->jaimepas as $jaimepas) {
            if ($jaimepas->getJoueur() === $joueur) {
                return true;
            }
        }

        return false;
    }


    public function like(Joueur $joueur): void
    {
        if (!$this->isLikedByJoueur($joueur)) {
            $jaimes = new Jaime();
            $jaimes->setEquipe($this);
            $jaimes->setJoueur($joueur);
            $this->jaimes[] = $jaimes;
        }
    }


    public function dislike(Joueur $joueur): void
    {
        if (!$this->isDislikedByJoueur($joueur)) {
            $Jaimepas = new Jaimepas();
            $Jaimepas->setEquipe($this);
            $Jaimepas->setJoueur($joueur);

            $this->jaimepas[] = $Jaimepas;
        }
    }
}