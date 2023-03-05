<?php

namespace App\Entity;

use App\Repository\LikeseqRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeseqRepository::class)]
class Likeseq
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $typel = null;

    #[ORM\ManyToOne(inversedBy: 'likeseqs')]
    private ?equipe $equipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypel(): ?int
    {
        return $this->typel;
    }

    public function setTypel(int $typel): self
    {
        $this->typel = $typel;

        return $this;
    }

    public function getEquipe(): ?equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }
}
