<?php

namespace App\State;

use App\Entity\Messagerie;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use Symfony\Bundle\SecurityBundle\Security;

class MessagerieProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly PersistProcessor $persistProcessor,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Ajouter l'expéditeur automatiquement (uniquement à la création)
        if ($data instanceof Messagerie) {
            $currentUser = $this->security->getUser();

            // On n'écrase l'expéditeur que si c'est une nouvelle entité (pas de PATCH)
            if ($currentUser && $data->getId() === null) {
                $data->setExpediteur($currentUser);
                $data->setDateEnvoie(new \DateTime());

                // Si c'est une offre, initialiser le statut
                if ($data->isEstOffre() === true) {
                    $data->setStatutOffre('en attente');
                }
            }
        }

        // Déléguer la persistence à API Platform
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
