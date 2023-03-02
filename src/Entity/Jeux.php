<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\SlugifyInterface;
use App\Entity\InvalidArgumentException;

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("jeuxes")]
    private ?int $id = null;

    #[ORM\Column(length: 8, unique: true)]
    #[Assert\NotBlank(message: "Reference de jeux est vide ")]
    #[Groups("jeuxes")]
    private ?string $ref = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Libelle de jeux est vide ")]
    #[Groups("jeuxes")]
    private ?string $libelle = null;

    #[ORM\ManyToMany(targetEntity: CategorieJeux::class)]
    #[Groups("jeuxes")]
    private Collection $Categories;

    #[ORM\ManyToMany(targetEntity: TypeJeux::class)]
    private Collection $types;

    #[ORM\Column(length: 255)]
    private ?string $LogoJeux = null;

    #[ORM\Column(length: 255)]
    private ?string $ImageJeux = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column]
    private ?float $note = null;

    #[ORM\Column]
    private ?int $noteCount = null;

    #[ORM\Column]
    private ?float $noteMyonne = null;

    public function __construct()
    {
        $this->Categories = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->dateCreation = new DateTime();
        
    }

    public function setRef(): self
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ref = '';
    for ($i = 0; $i < 8; $i++) {
        $ref .= $characters[random_int(0, strlen($characters) - 1)];
    }
    $this->ref = $ref;

    return $this;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }


    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }


    /**
     * @return Collection<int, CategorieJeux>
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(CategorieJeux $category): self
    {
        if (!$this->Categories->contains($category)) {
            $this->Categories->add($category);
        }

        return $this;
    }

    public function removeCategory(CategorieJeux $category): self
    {
        $this->Categories->removeElement($category);

        return $this;
    }

    public function getCategoryNames(): array
{
    $categoryNames = [];
    foreach ($this->Categories as $category) {
        $categoryNames[] = $category->getName();
    }
    return $categoryNames;
}

    /**
     * @return Collection<int, TypeJeux>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(TypeJeux $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }

        return $this;
    }

    public function removeType(TypeJeux $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    public function getLogoJeux(): ?string
    {
        return $this->LogoJeux;
    }

    public function setLogoJeux(string $LogoJeux): self
    {
        $this->LogoJeux = $LogoJeux;

        return $this;
    }

    public function getImageJeux(): ?string
    {
        return $this->ImageJeux;
    }

    public function setImageJeux(string $ImageJeux): self
    {
        $this->ImageJeux = $ImageJeux;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getSlug(SlugifyInterface $slugify): string
    {
        return $slugify->slugify($this->libelle);
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

  
    public function getNoteCount(): ?int
    {
        return $this->noteCount;
    }

    public function setNoteCount(int $noteCount): self
    {
        $this->noteCount = $noteCount;

        return $this;
    }

    public function getNoteMyonne(): ?float
    {
        return $this->noteMyonne;
    }

    public function setNoteMyonne(float $noteMyonne): self
    {
        $this->noteMyonne = $noteMyonne;

        return $this;
    }


    #[ORM\Column]
    private ?float $totalNote = null;
    public function setNote(float $note): self
    {
        
        $this->noteCount++;
        $this->totalNote += $note;
        $this->noteMyonne = $this->totalNote / $this->noteCount;
        $this->note = $note;
    
        return $this;
    }

    public function getTotalNote(): ?float
    {
        return $this->totalNote;
    }

    public function setTotalNote(float $totalNote): self
    {
        $this->totalNote = $totalNote;

        return $this;
    }
}
