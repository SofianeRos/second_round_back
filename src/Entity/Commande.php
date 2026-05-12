<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixFinal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $fraisPort = null;

    #[ORM\Column(length: 255)]
    private ?string $statutLivraison = null;

    #[ORM\Column]
    private ?\DateTime $dateCommande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $acheteur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixFinal(): ?string
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(string $prixFinal): static
    {
        $this->prixFinal = $prixFinal;

        return $this;
    }

    public function getFraisPort(): ?string
    {
        return $this->fraisPort;
    }

    public function setFraisPort(string $fraisPort): static
    {
        $this->fraisPort = $fraisPort;

        return $this;
    }

    public function getStatutLivraison(): ?string
    {
        return $this->statutLivraison;
    }

    public function setStatutLivraison(string $statutLivraison): static
    {
        $this->statutLivraison = $statutLivraison;

        return $this;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getAcheteur(): ?User
    {
        return $this->acheteur;
    }

    public function setAcheteur(?User $acheteur): static
    {
        $this->acheteur = $acheteur;

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
