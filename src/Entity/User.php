<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(nullable: true)]
    private ?int $tailleCm = null;

    #[ORM\Column(nullable: true)]
    private ?int $poidsKg = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeBoxe = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $budgetMax = null;

    #[ORM\Column]
    private ?\DateTime $dateInscription = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'vendeur')]
    private Collection $articles;

    /**
     * @var Collection<int, Favori>
     */
    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'user')]
    private Collection $favoris;

    /**
     * @var Collection<int, Messagerie>
     */
    #[ORM\OneToMany(targetEntity: Messagerie::class, mappedBy: 'expediteur')]
    private Collection $messageries;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'acheteur')]
    private Collection $commandes;

    /**
     * @var Collection<int, CommentaireArticle>
     */
    #[ORM\OneToMany(targetEntity: CommentaireArticle::class, mappedBy: 'auteur')]
    private Collection $commentaireArticles;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'userAuteur')]
    private Collection $evaluations;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->commentaireArticles = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getTailleCm(): ?int
    {
        return $this->tailleCm;
    }

    public function setTailleCm(?int $tailleCm): static
    {
        $this->tailleCm = $tailleCm;

        return $this;
    }

    public function getPoidsKg(): ?int
    {
        return $this->poidsKg;
    }

    public function setPoidsKg(?int $poidsKg): static
    {
        $this->poidsKg = $poidsKg;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getTypeBoxe(): ?string
    {
        return $this->typeBoxe;
    }

    public function setTypeBoxe(?string $typeBoxe): static
    {
        $this->typeBoxe = $typeBoxe;

        return $this;
    }

    public function getBudgetMax(): ?string
    {
        return $this->budgetMax;
    }

    public function setBudgetMax(?string $budgetMax): static
    {
        $this->budgetMax = $budgetMax;

        return $this;
    }

    public function getDateInscription(): ?\DateTime
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTime $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setVendeur($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getVendeur() === $this) {
                $article->setVendeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favori>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favori $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setUser($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUser() === $this) {
                $favori->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messagerie>
     */
    public function getMessageries(): Collection
    {
        return $this->messageries;
    }

    public function addMessagery(Messagerie $messagery): static
    {
        if (!$this->messageries->contains($messagery)) {
            $this->messageries->add($messagery);
            $messagery->setExpediteur($this);
        }

        return $this;
    }

    public function removeMessagery(Messagerie $messagery): static
    {
        if ($this->messageries->removeElement($messagery)) {
            // set the owning side to null (unless already changed)
            if ($messagery->getExpediteur() === $this) {
                $messagery->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setAcheteur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAcheteur() === $this) {
                $commande->setAcheteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentaireArticle>
     */
    public function getCommentaireArticles(): Collection
    {
        return $this->commentaireArticles;
    }

    public function addCommentaireArticle(CommentaireArticle $commentaireArticle): static
    {
        if (!$this->commentaireArticles->contains($commentaireArticle)) {
            $this->commentaireArticles->add($commentaireArticle);
            $commentaireArticle->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaireArticle(CommentaireArticle $commentaireArticle): static
    {
        if ($this->commentaireArticles->removeElement($commentaireArticle)) {
            // set the owning side to null (unless already changed)
            if ($commentaireArticle->getAuteur() === $this) {
                $commentaireArticle->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setUserAuteur($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getUserAuteur() === $this) {
                $evaluation->setUserAuteur(null);
            }
        }

        return $this;
    }

    // Méthodes requises pour l'authentification Symfony

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    // Méthodes requises par UserInterface

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
