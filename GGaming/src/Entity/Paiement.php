<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $refer = null;

    #[ORM\Column(length: 20)]
    private ?string $typeCard = null;

    #[ORM\Column(length: 255)]
    private ?string $numberCard = null;

    #[ORM\Column(length: 10)]
    private ?string $cvc = null;

    #[ORM\Column]
    private ?int $exp_month = null;

    #[ORM\Column]
    private ?int $exp_year = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefer(): ?string
    {
        return $this->refer;
    }

    public function setRefer(string $refer): self
    {
        $this->refer = $refer;

        return $this;
    }

    public function getTypeCard(): ?string
    {
        return $this->typeCard;
    }

    public function setTypeCard(string $typeCard): self
    {
        $this->typeCard = $typeCard;

        return $this;
    }

    public function getNumberCard(): ?string
    {
        return $this->numberCard;
    }

    public function setNumberCard(string $numberCard): self
    {
        $this->numberCard = $numberCard;

        return $this;
    }

    public function getCvc(): ?string
    {
        return $this->cvc;
    }

    public function setCvc(string $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getExpMonth(): ?int
    {
        return $this->exp_month;
    }

    public function setExpMonth(int $exp_month): self
    {
        $this->exp_month = $exp_month;

        return $this;
    }

    public function getExpYear(): ?int
    {
        return $this->exp_year;
    }

    public function setExpYear(int $exp_year): self
    {
        $this->exp_year = $exp_year;

        return $this;
    }
}
