<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\CommandeRepository;
use App\State\CommandeProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(security: "is_granted('view', object)"),
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(
            security: "is_granted('ROLE_USER')",
            processor: CommandeProcessor::class,
            denormalizationContext: ['groups' => ['write']],
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    paginationItemsPerPage: 20,
)]
#[ApiFilter(SearchFilter::class, properties: ['acheteur.id' => 'exact', 'article.id' => 'exact', 'statutLivraison' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['dateCommande' => 'DESC'])]
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['read', 'write'])]
    private ?string $prixFinal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['read', 'write'])]
    private ?string $fraisPort = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $statutLivraison = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTime $dateCommande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?User $acheteur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
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
