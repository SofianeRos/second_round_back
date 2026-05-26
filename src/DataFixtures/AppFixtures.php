<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Statut;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ====== STATUTS ======
        $statuts = [];
        foreach (['En vente', 'Vendu', 'Réservé', 'Retiré'] as $libelle) {
            $statut = new Statut();
            $statut->setLibelle($libelle);
            $statut->setDescription("Statut : $libelle");
            $statut->setCouleurBadge(match ($libelle) {
                'En vente' => '#10B981',
                'Vendu' => '#6B7280',
                'Réservé' => '#F59E0B',
                'Retiré' => '#EF4444',
                default => '#3B82F6'
            });
            $statut->setParDefaut($libelle === 'En vente');
            $manager->persist($statut);
            $statuts[$libelle] = $statut;
        }

        // ====== UTILISATEURS ======
        $users = [];

        // Utilisateur 1 : Lucas (acheteur étudiant)
        $lucas = new User();
        $lucas->setEmail('lucas@example.fr');
        $lucas->setPseudo('LucasBoxeur');
        $lucas->setTailleCm(175);
        $lucas->setPoidsKg(68);
        $lucas->setNiveau('Loisir');
        $lucas->setTypeBoxe('Boxe Anglaise');
        $lucas->setBudgetMax('200');
        $lucas->setDateInscription(new \DateTime('-30 days'));
        $lucas->setPassword($this->hasher->hashPassword($lucas, 'password123'));
        $manager->persist($lucas);
        $users['lucas'] = $lucas;

        // Utilisateur 2 : Sarah (vendeuse avec expérience)
        $sarah = new User();
        $sarah->setEmail('sarah@example.fr');
        $sarah->setPseudo('SarahChampion');
        $sarah->setTailleCm(168);
        $sarah->setPoidsKg(62);
        $sarah->setNiveau('Compétition');
        $sarah->setTypeBoxe('Boxe Anglaise');
        $sarah->setBudgetMax('500');
        $sarah->setDateInscription(new \DateTime('-60 days'));
        $sarah->setPassword($this->hasher->hashPassword($sarah, 'password456'));
        $manager->persist($sarah);
        $users['sarah'] = $sarah;

        // Utilisateur test (pour les tests API)
        $test = new User();
        $test->setEmail('test@boxe.fr');
        $test->setPseudo('TestBoxer');
        $test->setTailleCm(180);
        $test->setPoidsKg(75);
        $test->setNiveau('Loisir');
        $test->setTypeBoxe('MMA');
        $test->setDateInscription(new \DateTime());
        $test->setPassword($this->hasher->hashPassword($test, 'azerty'));
        $manager->persist($test);
        $users['test'] = $test;

        // Utilisateur Admin (pour les tests)
        $admin = new User();
        $admin->setEmail('admin@boxe.fr');
        $admin->setPseudo('AdminBoxe');
        $admin->setTailleCm(178);
        $admin->setPoidsKg(72);
        $admin->setNiveau('Compétition');
        $admin->setTypeBoxe('Boxe Anglaise');
        $admin->setDateInscription(new \DateTime());
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($admin);
        $users['admin'] = $admin;

        // ====== ARTICLES ======
        // Article 1 : Gants Venum (vendus par Sarah)
        $article1 = new Article();
        $article1->setCategorie('Gants');
        $article1->setMarque('Venum');
        $article1->setTaille('12 oz');
        $article1->setEtat('Très bon état');
        $article1->setPrix('45.00');
        $article1->setDescription('Gants de boxe Venum 12oz, quasi neufs, très peu utilisés. Parfaits pour l\'entraînement. Couleur noir/rouge.');
        $article1->setDatePublication(new \DateTime('-7 days'));
        $article1->setVendeur($users['sarah']);
        $article1->setStatut($statuts['En vente']);
        $manager->persist($article1);

        // Article 2 : Protège-dents Evenflo
        $article2 = new Article();
        $article2->setCategorie('Protection');
        $article2->setMarque('Evenflo');
        $article2->setTaille('Adulte');
        $article2->setEtat('Neuf');
        $article2->setPrix('15.00');
        $article2->setDescription('Protège-dents neuf, jamais utilisé. Modèle universel adaptable.');
        $article2->setDatePublication(new \DateTime('-3 days'));
        $article2->setVendeur($users['lucas']);
        $article2->setStatut($statuts['En vente']);
        $manager->persist($article2);

        // Article 3 : Bandes de boxe Ringside
        $article3 = new Article();
        $article3->setCategorie('Bandes');
        $article3->setMarque('Ringside');
        $article3->setTaille('4,5m');
        $article3->setEtat('Bon état');
        $article3->setPrix('8.00');
        $article3->setDescription('Paire de bandes de boxe Ringside 4,5m. Utilisées 5-6 fois. Hygiène respectée.');
        $article3->setDatePublication(new \DateTime('-15 days'));
        $article3->setVendeur($users['sarah']);
        $article3->setStatut($statuts['En vente']);
        $manager->persist($article3);

        // Article 4 : Casque de boxe Fairtex (Vendu)
        $article4 = new Article();
        $article4->setCategorie('Casques');
        $article4->setMarque('Fairtex');
        $article4->setTaille('M');
        $article4->setEtat('Excellent état');
        $article4->setPrix('120.00');
        $article4->setDescription('Casque de boxe Fairtex de qualité professionnelle. Peu utilisé, parfait pour les sparrings.');
        $article4->setDatePublication(new \DateTime('-30 days'));
        $article4->setVendeur($users['sarah']);
        $article4->setStatut($statuts['Vendu']);
        $manager->persist($article4);

        $manager->flush();
    }
}
