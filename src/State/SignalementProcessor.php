<?php

namespace App\State;

use App\Entity\Signalement;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

class SignalementProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly PersistProcessor $persistProcessor,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Signalement) {
            $currentUser = $this->security->getUser();

            // Creation (POST)
            if ($data->getId() === null) {
                if ($currentUser) {
                    $data->setSignalePar($currentUser);
                }
                $data->setDateSignalement(new \DateTimeImmutable());
                $data->setStatut('en_attente');
            } else {
                // Update (PATCH)
                // If statut is changed to 'traite_sanctionne', ban the sender
                if ($data->getStatut() === 'traite_sanctionne') {
                    $message = $data->getMessage();
                    if ($message) {
                        $sender = $message->getExpediteur();
                        if ($sender) {
                            $sender->setBanni(true);
                            $this->entityManager->persist($sender);
                            $this->entityManager->flush();
                        }
                    }
                }
            }
        }

        // Delegate persistence of Signalement to API Platform
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
