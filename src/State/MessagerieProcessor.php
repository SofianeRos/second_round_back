<?php

namespace App\State;

use App\Entity\Messagerie;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MessagerieProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Ajouter l'expéditeur automatiquement
        if ($data instanceof Messagerie) {
            $currentUser = $this->security->getUser();
            if ($currentUser) {
                $data->setExpediteur($currentUser);
            }

            // Ajouter la date d'envoi
            $data->setDateEnvoie(new \DateTime());

            // Si c'est une offre, initialiser le statut
            if ($data->isEstOffre() === true) {
                $data->setStatutOffre('en attente');
            }
        }

        return $data;
    }
}
