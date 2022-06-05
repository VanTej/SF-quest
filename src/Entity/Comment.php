<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private ?User $author;

    #[ORM\ManyToOne(targetEntity: Episode::class, inversedBy: 'comments')]
    private ?Episode $episode;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    private $comment;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Regex('/[0-5]{1}/', message: 'La note est sur 5')]
    private $rate;

    public function __construct(?Episode $episode, ?User $user)
    {
        $this->episode = $episode;
        $this->author = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(?Episode $episode): self
    {
        $this->episode = $episode;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
