<?php

namespace App\State;

use App\Entity\Commande;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CommandeProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Ajouter l'acheteur automatiquement
        if ($data instanceof Commande) {
            $currentUser = $this->security->getUser();
            if ($currentUser) {
                $data->setAcheteur($currentUser);
            }

            // Ajouter la date de commande
            $data->setDateCommande(new \DateTime());

            // Initialiser le statut de livraison
            if (!$data->getStatutLivraison()) {
                $data->setStatutLivraison('en attente');
            }
        }

        return $data;
    }
}
