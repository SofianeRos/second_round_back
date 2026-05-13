<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        // On récupère l'outil de hachage de mot de passe
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. On crée un utilisateur de test
        $user = new User();
        $user->setEmail('test@boxe.fr');
        $user->setPseudo('TestBoxer');
        $user->setDateInscription(new \DateTime());

        // 2. On hache le mot de passe "azerty"
        $hashedPassword = $this->hasher->hashPassword($user, 'azerty');
        $user->setPassword($hashedPassword);

        // 3. On demande à Doctrine de l'enregistrer
        $manager->persist($user);

        // 4. On valide l'écriture en base de données
        $manager->flush();
    }
}
