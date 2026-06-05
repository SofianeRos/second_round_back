<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read'], 'enable_max_depth' => true],
    denormalizationContext: ['groups' => ['write']],
    paginationItemsPerPage: 20,
    paginationPartial: true,
)]
#[ApiFilter(SearchFilter::class, properties: ['categorie' => 'exact', 'marque' => 'partial', 'description' => 'partial', 'statut.id' => 'exact', 'vendeur.id' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['prix'])]
#[ApiFilter(OrderFilter::class, properties: ['prix' => 'ASC', 'datePublication' => 'DESC'])]
#[Get()]
#[GetCollection()]
#[Post(security: "is_granted('ROLE_USER')")]
#[Patch(security: "is_granted('edit', object)")]
#[Delete(security: "is_granted('delete', object)")]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $taille = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $etat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['read', 'write'])]
    private ?string $prix = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTime $datePublication = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?User $vendeur = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?Statut $statut = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'article')]
    #[Groups(['read'])]
    private Collection $photos;

    /**
     * @var Collection<int, Favori>
     */
    #[ORM\OneToMany(targetEntity: Favori::class, mappedBy: 'article')]
    private Collection $favoris;

    /**
     * @var Collection<int, Messagerie>
     */
    #[ORM\OneToMany(targetEntity: Messagerie::class, mappedBy: 'article')]
    private Collection $messageries;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'article')]
    private Collection $commandes;

    /**
     * @var Collection<int, CommentaireArticle>
     */
    #[ORM\OneToMany(targetEntity: CommentaireArticle::class, mappedBy: 'article')]
    private Collection $commentaireArticles;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->commentaireArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getVendeur(): ?User
    {
        return $this->vendeur;
    }

    public function setVendeur(?User $vendeur): static
    {
        $this->vendeur = $vendeur;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setArticle($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getArticle() === $this) {
                $photo->setArticle(null);
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
            $favori->setArticle($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getArticle() === $this) {
                $favori->setArticle(null);
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
            $messagery->setArticle($this);
        }

        return $this;
    }

    public function removeMessagery(Messagerie $messagery): static
    {
        if ($this->messageries->removeElement($messagery)) {
            // set the owning side to null (unless already changed)
            if ($messagery->getArticle() === $this) {
                $messagery->setArticle(null);
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
            $commande->setArticle($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getArticle() === $this) {
                $commande->setArticle(null);
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
            $commentaireArticle->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaireArticle(CommentaireArticle $commentaireArticle): static
    {
        if ($this->commentaireArticles->removeElement($commentaireArticle)) {
            // set the owning side to null (unless already changed)
            if ($commentaireArticle->getArticle() === $this) {
                $commentaireArticle->setArticle(null);
            }
        }

        return $this;
    }
}
