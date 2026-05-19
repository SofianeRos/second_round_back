<?php

namespace App\Security;

use App\Entity\Commande;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommandeVoter extends Voter
{
    public const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW]) && $subject instanceof Commande;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Commande $commande */
        $commande = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($commande, $user);
        }

        return false;
    }

    private function canView(Commande $commande, User $user): bool
    {
        // L'acheteur et le vendeur (propriétaire de l'article) peuvent voir la commande
        return $commande->getAcheteur() === $user || $commande->getArticle()->getVendeur() === $user;
    }
}
