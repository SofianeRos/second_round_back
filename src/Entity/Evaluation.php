<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\EvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?\DateTime $dateEvaluation = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userAuteur = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userCible = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateEvaluation(): ?\DateTime
    {
        return $this->dateEvaluation;
    }

    public function setDateEvaluation(\DateTime $dateEvaluation): static
    {
        $this->dateEvaluation = $dateEvaluation;

        return $this;
    }

    public function getUserAuteur(): ?User
    {
        return $this->userAuteur;
    }

    public function setUserAuteur(?User $userAuteur): static
    {
        $this->userAuteur = $userAuteur;

        return $this;
    }

    public function getUserCible(): ?User
    {
        return $this->userCible;
    }

    public function setUserCible(?User $userCible): static
    {
        $this->userCible = $userCible;

        return $this;
    }
}
