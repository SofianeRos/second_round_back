<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\CommentaireArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: CommentaireArticleRepository::class)]
class CommentaireArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTime $datePublication = null;

    #[ORM\ManyToOne(inversedBy: 'commentaireArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToOne(inversedBy: 'commentaireArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTime $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }
}
