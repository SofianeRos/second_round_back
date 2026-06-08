<?php

namespace App\State;

use App\Entity\Article;
use App\Entity\Statut;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ArticleProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly PersistProcessor $persistProcessor,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Article) {
            $currentUser = $this->security->getUser();

            if ($currentUser && $data->getId() === null) {
                // Associer l'utilisateur connecté comme vendeur
                $data->setVendeur($currentUser);
                
                // Définir la date de publication à maintenant
                $data->setDatePublication(new \DateTime());

                // Définir le statut par défaut "En vente"
                $statutRepo = $this->entityManager->getRepository(Statut::class);
                $defaultStatut = $statutRepo->findOneBy(['parDefaut' => true]) 
                    ?? $statutRepo->findOneBy(['libelle' => 'En vente']);
                
                if ($defaultStatut) {
                    $data->setStatut($defaultStatut);
                }
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
