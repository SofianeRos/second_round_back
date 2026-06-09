<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Repository\SignalementRepository;
use App\State\SignalementProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Post(
            security: "is_granted('ROLE_USER')",
            processor: SignalementProcessor::class,
            denormalizationContext: ['groups' => ['write']]
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            processor: SignalementProcessor::class,
            denormalizationContext: ['groups' => ['write']]
        )
    ],
    normalizationContext: ['groups' => ['read']],
    order: ['dateSignalement' => 'DESC']
)]
#[ORM\Entity(repositoryClass: SignalementRepository::class)]
class Signalement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private ?Messagerie $message = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['read'])]
    private ?User $signalePar = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $raison = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $dateSignalement = null;

    #[ORM\Column(length: 30)]
    #[Groups(['read', 'write'])]
    private ?string $statut = 'en_attente'; // 'en_attente', 'traite_sanctionne', 'traite_ignore'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?Messagerie
    {
        return $this->message;
    }

    public function setMessage(?Messagerie $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSignalePar(): ?User
    {
        return $this->signalePar;
    }

    public function setSignalePar(?User $signalePar): static
    {
        $this->signalePar = $signalePar;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    public function getDateSignalement(): ?\DateTimeImmutable
    {
        return $this->dateSignalement;
    }

    public function setDateSignalement(\DateTimeImmutable $dateSignalement): static
    {
        $this->dateSignalement = $dateSignalement;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
