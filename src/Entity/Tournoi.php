<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\Slugify;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("tournoi")]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un nom de tournoi")]
    #[Groups("tournoi")]
    private ?string $nomTournoi = null;

    #[ORM\Column(nullable: true)]
    #[Groups("tournoi")]
    private ?int $participantTotal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un nom d'organisateur")]
    #[Groups("tournoi")]
    private ?string $nomHote = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThan('today',message:"La valeur saisie doit depasser la date d'aujourhui")]
    #[Assert\NotBlank(message:"Il faut saisir une date")]
    #[Groups("tournoi")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un nombre")]
    #[Assert\GreaterThan(-1,message:"La valeur saisie doit etre au minmum 0")]
    #[Groups("tournoi")]
    private ?int $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un type")]
    #[Groups("tournoi")]
    private ?string $typeTournoi = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Image]
    #[Groups("tournoi")]
    private ?string $imageTournoi = null;

    #[ORM\OneToMany(mappedBy: 'idTournoi', targetEntity: Matches::class, orphanRemoval: true)]
    private Collection $matches;

  /*  #[ORM\ManyToOne(inversedBy: 'tournois')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("tournoi")]
    private ?Jeu $jeu = null;*/


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("tournoi")]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("tournoi")]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'tournois')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("tournoi")]
    private ?Jeux $jeu = null;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
    }
    
    public function __toString(): string
    {
        return $this->nomTournoi;  
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(?string $nomTournoi): self
    {
        $this->nomTournoi = $nomTournoi;

        return $this;
    }

    public function getParticipantTotal(): ?int
    {
        return $this->participantTotal;
    }

    public function setParticipantTotal(?int $participantTotal): self
    {
        $this->participantTotal = $participantTotal;

        return $this;
    }

    public function getNomHote(): ?string
    {
        return $this->nomHote;
    }

    public function setNomHote(?string $nomHote): self
    {
        $this->nomHote = $nomHote;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTypeTournoi(): ?string
    {
        return $this->typeTournoi;
    }

    public function setTypeTournoi(?string $typeTournoi): self
    {
        $this->typeTournoi = $typeTournoi;

        return $this;
    }

    public function getImageTournoi(): ?string
    {
        return $this->imageTournoi;
    }

    public function setImageTournoi(?string $imageTournoi): self
    {
        $this->imageTournoi = $imageTournoi;

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(Matches $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setIdTournoi($this);
        }

        return $this;
    }

    public function removeMatch(Matches $match): self
    {
        if ($this->matches->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getIdTournoi() === $this) {
                $match->setIdTournoi(null);
            }
        }

        return $this;
    }

   

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $nomTournoi): self
    {
        $rand=(rand());
        $this->slug = (new Slugify())->slugify($nomTournoi.$rand);

        return $this;
    }

    public function getJeu(): ?Jeux
    {
        return $this->jeu;
    }

    public function setJeu(?Jeux $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }
}
