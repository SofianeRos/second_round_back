<?php

namespace App\Security;

use App\Entity\Messagerie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MessagerieVoter extends Voter
{
    public const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW]) && $subject instanceof Messagerie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Messagerie $messagerie */
        $messagerie = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($messagerie, $user);
        }

        return false;
    }

    private function canView(Messagerie $messagerie, User $user): bool
    {
        // L'expéditeur et le destinataire peuvent voir le message
        return $messagerie->getExpediteur() === $user || $messagerie->getDestinataire() === $user;
    }
}
