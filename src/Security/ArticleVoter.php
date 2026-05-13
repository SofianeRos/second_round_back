<?php

namespace App\Security;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas authentifié, on refuse
        if (!$user instanceof User) {
            return false;
        }

        /** @var Article $article */
        $article = $subject;

        return match ($attribute) {
            self::EDIT, self::DELETE => $this->canEditOrDelete($article, $user),
            default => false,
        };
    }

    private function canEditOrDelete(Article $article, User $user): bool
    {
        // Seul le vendeur peut modifier ou supprimer son article
        return $article->getVendeur() === $user;
    }
}
