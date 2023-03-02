<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Cocur\Slugify\Slugify;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatchesRepository::class)]
/*#[UniqueEntity(
    fields: ['equipe1', 'equipe2'],
    message: 'Choisir une equipe differente',
)]*/
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("matches")]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir le numero du tour")]
    #[Assert\GreaterThan(0,message:"La valeur saisie doit etre au minmum 1")]
    #[Groups("matches")]
    private ?string $tourActuel = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un score")]
    #[Assert\GreaterThan(-1,message:"La valeur saisie doit etre au minmum 0")]
    #[Groups("matches")]
    private ?int $scoreEquipeA = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Il faut saisir un score")]
    #[Assert\GreaterThan(-1,message:"La valeur saisie doit etre au minmum 0")]
    #[Groups("matches")]
    private ?int $scoreEquipeB = null;

    #[ORM\ManyToOne(inversedBy: 'matches')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"Il faut saisir un identifiant")]
    #[Groups("matches")]
    private ?Tournoi $idTournoi = null;

    #[ORM\ManyToOne(inversedBy: 'nomEquipe1')]
    #[Assert\NotBlank(message:"Il faut choisir une equipe")]
   // #[Assert\NotIdenticalTo($equipe2)]
   #[Groups("matches")] 
   private ?Equipe $equipe1 = null;

    #[ORM\ManyToOne(inversedBy: 'nomEquipe2')]
    #[Assert\NotBlank(message:"Il faut choisir une equipe")]
    //#[Assert\NotIdenticalTo($equipe1)]
    #[Groups("matches")]
    private ?Equipe $equipe2 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("matches")]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("matches")]
    private ?string $slug = null;

    public function __construct()
    {
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourActuel(): ?string
    {
        return $this->tourActuel;
    }

    public function setTourActuel(?string $tourActuel): self
    {
        $this->tourActuel = $tourActuel;

        return $this;
    }

    public function getScoreEquipeA(): ?int
    {
        return $this->scoreEquipeA;
    }

    public function setScoreEquipeA(?int $scoreEquipeA): self
    {
        $this->scoreEquipeA = $scoreEquipeA;

        return $this;
    }

    public function getScoreEquipeB(): ?int
    {
        return $this->scoreEquipeB;
    }

    public function setScoreEquipeB(?int $scoreEquipeB): self
    {
        $this->scoreEquipeB = $scoreEquipeB;

        return $this;
    }

    public function getIdTournoi(): ?Tournoi
    {
        return $this->idTournoi;
    }

    public function setIdTournoi(?Tournoi $idTournoi): self
    {
        $this->idTournoi = $idTournoi;

        return $this;
    }

    public function getEquipe1(): ?Equipe
    {
        return $this->equipe1;
    }

    public function setEquipe1(?Equipe $equipe1): self
    {
        $this->equipe1 = $equipe1;

        return $this;
    }

    public function getEquipe2(): ?Equipe
    {
        return $this->equipe2;
    }

    public function setEquipe2(?Equipe $equipe2): self
    {
        $this->equipe2 = $equipe2;

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

    public function setSlug(?string $equipe1,?string $equipe2): self
    {
        $rand=(rand());
        $this->slug = (new Slugify())->slugify($equipe1.$equipe2.$rand);

        return $this;
    }


}
