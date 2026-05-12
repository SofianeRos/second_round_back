<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\MessagerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: MessagerieRepository::class)]
class Messagerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $estOffre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantOffre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statutOffre = null;

    #[ORM\Column]
    private ?\DateTime $dateEnvoie = null;

    #[ORM\ManyToOne(inversedBy: 'messageries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $expediteur = null;

    #[ORM\ManyToOne(inversedBy: 'messageries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'messageries')]
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
