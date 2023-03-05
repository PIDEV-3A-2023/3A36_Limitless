<?php

namespace App\Entity;

use App\Repository\SponsorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
#[ORM\Entity(repositoryClass: SponsorRepository::class)]
#[UniqueEntity(fields: ['nom_sponsor'], message: 'nom d equipe déja utilisé')]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"veuillez choisir un nom de sponsor")]
    #[Groups("sponsor")]
    private ?string $nom_sponsor = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"veuillez choisir une description du sponsor")]
    #[Groups("sponsor")]
    private ?string $description_sponsor = null;

    #[ORM\Column(length: 255)]
    #[Groups("sponsor")]
    private ?string $logo_sponsor = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"veuillez choisir un site web")]
    #[Groups("sponsor")]
    private ?string $site_webs = null;

    #[ORM\ManyToOne(inversedBy: 'sponsors')]
    #[Assert\NotBlank(message:"veuillez choisir une equipe à sponsoriser")]
    #[Groups("sponsor")]
    private ?Equipe $id_equipe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
     /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $date_creationn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSponsor(): ?string
    {
        return $this->nom_sponsor;
    }

    public function setNomSponsor(string $nom_sponsor): self
    {
        $this->nom_sponsor = $nom_sponsor;

        return $this;
    }

    public function getDescriptionSponsor(): ?string
    {
        return $this->description_sponsor;
    }

    public function setDescriptionSponsor(string $description_sponsor): self
    {
        $this->description_sponsor = $description_sponsor;

        return $this;
    }

    public function getLogoSponsor(): ?string
    {
        return $this->logo_sponsor;
    }

    public function setLogoSponsor(string $logo_sponsor): self
    {
        $this->logo_sponsor = $logo_sponsor;

        return $this;
    }

    public function getSiteWebs(): ?string
    {
        return $this->site_webs;
    }

    public function setSiteWebs(string $site_webs): self
    {
        $this->site_webs = $site_webs;

        return $this;
    }

    public function getIdEquipe(): ?Equipe
    {
        return $this->id_equipe;
    }

    public function setIdEquipe(?Equipe $id_equipe): self
    {
        $this->id_equipe = $id_equipe;

        return $this;
    }

    public function getDateCreationn(): ?\DateTimeInterface
    {
        return $this->date_creationn;
    }
/**
     * @ORM\PrePersist()
     */
    public function setDateCreationn(\DateTimeInterface $date_creationn): self
    {
        $this->date_creationn = $date_creationn;

        return $this;
    }
}
