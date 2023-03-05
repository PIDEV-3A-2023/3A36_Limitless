<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use App\Entity\CategorieProduit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $id = null;
   /* public function __construct()
    {
        $this->dateProduit=new \DateTime();
    }*/

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire")]
    #[Groups("produit")]
    private ?string $nom = null;

    #[Assert\GreaterThan(value:0,message:"La quantite doit être positive")]
    #[Assert\NotBlank(message:"La quantité est obligatoire")]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $quantite = null;

    //#[Assert\GreaterThan(value:0,message:"Le prix doit être positif")]
    #[Assert\NotBlank(message:"Le prix est obligatoire")]
    #[Assert\Regex(pattern:'/^[0-9]+([.,][0-9]+)?$/',message:"Le prix ne peut être qu'un nombre positif.")]
    #[ORM\Column]
    #[Groups("produit")]
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
    #[Groups("produit")]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'produit')]
    #[Groups("produit")]
    private ?CategorieProduit $categorieProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("produit")]
    private ?string $refer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("produit")]
    private ?\DateTimeInterface $dateProduit = null;

    #[Assert\NotBlank(message:"La description est obligatoire")]
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("produit")]
    private ?string $description = null;


    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Likes::class,cascade:['persist','remove'])]
    #[Groups("produit")]
    private Collection $likes;

    #[ORM\Column(nullable: true)]
    #[Groups("produit")]
    private ?int $nbreEtoile = null;

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

    public function getNbreEtoile(): ?int
    {
        return $this->nbreEtoile;
    }

    public function setNbreEtoile(?int $nbreEtoile): self
    {
        $this->nbreEtoile = $nbreEtoile;

        return $this;
    }

    public function getStars(): float
   {
    $likes = $this->getLikes()->filter(function (Likes $like) {
        return $like->getType() === 1;
    })->count();

    $dislikes = $this->getLikes()->filter(function (Likes $like) {
        return $like->getType() === 0;
    })->count();

    $total = $likes + $dislikes;

    if ($total == 0) {
        return 0;
    }

    $z = 1.96; // z-score de 95% de confiance
    $p = $likes / $total;
    $stars = ($p + $z * $z / (2 * $total) - $z * sqrt(($p * (1 - $p) + $z * $z / (4 * $total)) / $total)) / (1 + $z * $z / $total);

    return round($stars * 5);
  }
   
}
