<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\MessagerieRepository;
use App\State\MessagerieProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(security: "is_granted('view', object)"),
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(
            security: "is_granted('ROLE_USER')",
            processor: MessagerieProcessor::class,
            denormalizationContext: ['groups' => ['write']],
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    paginationItemsPerPage: 50,
)]
#[ApiFilter(SearchFilter::class, properties: ['expediteur.id' => 'exact', 'destinataire.id' => 'exact', 'article.id' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['dateEnvoie' => 'DESC'])]
#[ORM\Entity(repositoryClass: MessagerieRepository::class)]
class Messagerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read', 'write'])]
    private ?string $contenu = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?bool $estOffre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $montantOffre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    private ?string $statutOffre = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTime $dateEnvoie = null;

    #[ORM\ManyToOne(inversedBy: 'messagesEnvoyes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?User $expediteur = null;

    #[ORM\ManyToOne(inversedBy: 'messagesRecus')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'messageries')]
    #[Groups(['read', 'write'])]
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

    public function isEstOffre(): ?bool
    {
        return $this->estOffre;
    }

    public function setEstOffre(bool $estOffre): static
    {
        $this->estOffre = $estOffre;

        return $this;
    }

    public function getMontantOffre(): ?string
    {
        return $this->montantOffre;
    }

    public function setMontantOffre(?string $montantOffre): static
    {
        $this->montantOffre = $montantOffre;

        return $this;
    }

    public function getStatutOffre(): ?string
    {
        return $this->statutOffre;
    }

    public function setStatutOffre(?string $statutOffre): static
    {
        $this->statutOffre = $statutOffre;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTime
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(\DateTime $dateEnvoie): static
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getExpediteur(): ?User
    {
        return $this->expediteur;
    }

    public function setExpediteur(?User $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?User
    {
        return $this->destinataire;
    }

    public function setDestinataire(?User $destinataire): static
    {
        $this->destinataire = $destinataire;

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
