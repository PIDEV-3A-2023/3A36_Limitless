<?php

namespace App\Entity;

use App\Repository\LikeBlogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LikeBlogRepository::class)]
class LikeBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("likes")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likeBlogs')]
    #[Groups("likes")]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(inversedBy: 'likeBlogs')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

}
