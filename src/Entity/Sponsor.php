<?php

namespace App\Entity;

use App\Repository\SponsorRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: SponsorRepository::class)]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_sponsor = null;

    #[ORM\Column(length: 255)]
    private ?string $description_sponsor = null;

    #[ORM\Column(length: 255)]
    private ?string $logo_sponsor = null;

    #[ORM\Column(length: 255)]
    private ?string $site_webs = null;

    #[ORM\ManyToOne(inversedBy: 'sponsors')]
    private ?Equipe $id_equipe = null;

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
}
