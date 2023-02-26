<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use App\Entity\CategorieProduit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
   /* public function __construct()
    {
        $this->dateProduit=new \DateTime();
    }*/

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire")]
    private ?string $nom = null;

    #[Assert\GreaterThan(value:0,message:"La quantite doit être positive")]
    #[Assert\NotBlank(message:"La quantité est obligatoire")]
    #[ORM\Column]
    private ?int $quantite = null;

    //#[Assert\GreaterThan(value:0,message:"Le prix doit être positif")]
    #[Assert\NotBlank(message:"Le prix est obligatoire")]
    #[Assert\Regex(pattern:'/^[0-9]+([.,][0-9]+)?$/',message:"Le prix ne peut être qu'un nombre positif.")]
    #[ORM\Column]
    private ?string $prix = null;


    #[Assert\NotBlank(message:"Vous devez insérer une image")]
    #[Assert\File(
            maxSize:'1024k',
            mimeTypes:[
                'image/jpeg',
                'image/png',
            ],
            mimeTypesMessage:'Please upload a valid image (JPEG or PNG).',
            maxSizeMessage:'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum doit-être {{ limit }} {{ suffix }}.'
        )]

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'produit')]
    private ?CategorieProduit $categorieProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateProduit = null;

    #[Assert\NotBlank(message:"La description est obligatoire")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $picture = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Likes::class)]
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorieProduit(): ?CategorieProduit
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(?CategorieProduit $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;

        return $this;
    }

    public function getRefer(): ?string
    {
        return $this->refer;
    }

    public function setRefer(?string $refer): self
    {
        $this->refer = $refer;

        return $this;
    }

    public function getDateProduit(): ?\DateTimeInterface
    {
        return $this->dateProduit;
    }

    public function setDateProduit(?\DateTimeInterface $dateProduit): self
    {
        $this->dateProduit = $dateProduit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Likes>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setProduit($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getProduit() === $this) {
                $like->setProduit(null);
            }
        }

        return $this;
    }

   
}
