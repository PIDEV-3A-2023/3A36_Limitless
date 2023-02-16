<?php

namespace App\Entity;

use App\Repository\EssaiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EssaiRepository::class)]
class Essai
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
