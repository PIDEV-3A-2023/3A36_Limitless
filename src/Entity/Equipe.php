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



#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Index(name: 'equipe', columns: ['nom_equipe', 'description_equipe'], flags: ['fulltext'])]
#[UniqueEntity(fields: ['nom_equipe'], message: 'nom d equipe déja utilisé')]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)] 
    #[Assert\NotBlank(message:"veuillez choisir un nom d equipe")]
    #[Groups("equipe")]
    private ?string $nom_equipe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"veuillez choisir une description d'equipe")]
    #[Groups("equipe")]
    private ?string $description_equipe = null;

    #[ORM\Column]
     /**
     * @ORM\Column(type="integer")
     
     * @Assert\NotBlank(message="Le nombre de joueurs est obligatoire")
     */
    #[Groups("equipe")]
    private ?int $nb_joueurs = null;

    #[ORM\Column(length: 255)]
    #[Groups("equipe")]
    private ?string $logo_equipe = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank(message:"veuillez choisir un site web")]
    #[Groups("equipe")]
    private ?string $site_web = null;

    #[ORM\OneToMany(mappedBy: 'id_equipe', targetEntity: Sponsor::class, cascade: ["remove"], orphanRemoval: true)]
    private Collection $sponsors;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
     /**
     * @ORM\Column(type="datetime")
     */
    #[Groups("equipe")]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Likeseq::class, cascade: ["remove"], orphanRemoval: true)]
    private Collection $likeseqs;

    #[ORM\Column]
    #[Groups("equipe")]
    private ?int $rating = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Jaime::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $jaimes;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Jaimepas::class, cascade:["persist","remove","merge"], orphanRemoval:true)]
    private Collection $jaimepas;

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    private ?Joueur $joueur = null;

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    private ?User $user = null;

    public function __construct()
    {
        $this->sponsors = new ArrayCollection();
        $this->likeseqs = new ArrayCollection();
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
     * @return Collection<int, Likeseq>
     */
    public function getLikeseqs(): Collection
    {
        return $this->likeseqs;
    }

    public function addLikeseq(Likeseq $likeseq): self
    {
        if (!$this->likeseqs->contains($likeseq)) {
            $this->likeseqs->add($likeseq);
            $likeseq->setEquipe($this);
        }

        return $this;
    }

    public function removeLikeseq(Likeseq $likeseq): self
    {
        if ($this->likeseqs->removeElement($likeseq)) {
            // set the owning side to null (unless already changed)
            if ($likeseq->getEquipe() === $this) {
                $likeseq->setEquipe(null);
            }
        }

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRatingg(): float
    {
     $likeseqs = $this->getLikeseqs()->filter(function (Likeseq $likeseq) {
         return $likeq->getType() === 1;
     })->count();
 
     $dislikeseqs = $this->getLikeseqs()->filter(function (Likeseq $likeseq) {
         return $likeq->getType() === 0;
     })->count();
 
     $totals = $likeseqs + $dislikeseqs;
 
     if ($totals == 0) {
         return 0;
     }
 
     $zz = 1.96; // z-score de 95% de confiance
     $pp = $likeseqs / $totals;
     $starss = ($pp + $zz * $zz / (2 * $totals) - $zz * sqrt(($pp * (1 - $pp) + $zz * $zz / (4 * $totals)) / $totals)) / (1 + $zz * $zz / $totals);
 
     return round($starss * 5);
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

    public function isLikedByJoueur(User $user): bool
    {
        foreach ($this->jaimes as $jaimes) {
            if ($jaimes->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function isDislikedByJoueur(User $user): bool
    {
        foreach ($this->jaimepas as $jaimepas) {
            if ($jaimepas->getUser() === $user) {
                return true;
            }
        }

        return false;
    }


    public function like(User $user): void
    {
        if (!$this->isLikedByJoueur($user)) {
            $jaimes = new Jaime();
            $jaimes->setEquipe($this);
            $jaimes->setUser($user);
            $this->jaimes[] = $jaimes;
        }
    }


    public function dislike(User $user): void
    {
        if (!$this->isDislikedByJoueur($user)) {
            $Jaimepas = new Jaimepas();
            $Jaimepas->setEquipe($this);
            $Jaimepas->setUser($user);

            $this->jaimepas[] = $Jaimepas;
        }
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    
}
