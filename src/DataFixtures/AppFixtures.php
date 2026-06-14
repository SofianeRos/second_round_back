<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Messagerie;
use App\Entity\Photo;
use App\Entity\Statut;
use App\Entity\User;
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
        // ═══════════════════════════════════════════════════
        //  STATUTS
        // ═══════════════════════════════════════════════════
        $statuts = [];
        foreach (['En vente', 'Vendu', 'Réservé', 'Retiré'] as $libelle) {
            $statut = new Statut();
            $statut->setLibelle($libelle);
            $statut->setDescription("Statut : $libelle");
            $statut->setCouleurBadge(match ($libelle) {
                'En vente' => '#10B981',
                'Vendu'    => '#6B7280',
                'Réservé'  => '#F59E0B',
                'Retiré'   => '#EF4444',
                default    => '#3B82F6'
            });
            $statut->setParDefaut($libelle === 'En vente');
            $manager->persist($statut);
            $statuts[$libelle] = $statut;
        }

        // ═══════════════════════════════════════════════════
        //  UTILISATEURS
        //  Identifiants de test :
        //  • test@boxe.fr / azerty            (acheteur principal)
        //  • jayson@boxe.fr / password123     (vendeur 1)
        //  • marvin@boxe.fr / password123     (vendeur 2)
        //  • lou@boxe.fr / password123        (vendeur 3)
        //  • admin@boxe.fr / admin123         (admin)
        // ═══════════════════════════════════════════════════

        // — Acheteur principal (compte de test) —
        $test = new User();
        $test->setEmail('test@boxe.fr');
        $test->setPseudo('TestBoxer');
        $test->setTailleCm(180);
        $test->setPoidsKg(75);
        $test->setNiveau('Loisir');
        $test->setTypeBoxe('MMA');
        $test->setBudgetMax('300');
        $test->setDateInscription(new \DateTime('-10 days'));
        $test->setPassword($this->hasher->hashPassword($test, 'azerty'));
        $manager->persist($test);

        // — Jayson51 — vendeur gants & casques —
        $jayson = new User();
        $jayson->setEmail('jayson@boxe.fr');
        $jayson->setPseudo('Jayson51');
        $jayson->setTailleCm(183);
        $jayson->setPoidsKg(80);
        $jayson->setNiveau('Compétition');
        $jayson->setTypeBoxe('Boxe Anglaise');
        $jayson->setBudgetMax('600');
        $jayson->setDateInscription(new \DateTime('-90 days'));
        $jayson->setPassword($this->hasher->hashPassword($jayson, 'password123'));
        $manager->persist($jayson);

        // — Marvin32 — vendeur équipements de frappe —
        $marvin = new User();
        $marvin->setEmail('marvin@boxe.fr');
        $marvin->setPseudo('Marvin32');
        $marvin->setTailleCm(176);
        $marvin->setPoidsKg(72);
        $marvin->setNiveau('Amateur');
        $marvin->setTypeBoxe('Kick-boxing');
        $marvin->setBudgetMax('400');
        $marvin->setDateInscription(new \DateTime('-120 days'));
        $marvin->setPassword($this->hasher->hashPassword($marvin, 'password123'));
        $manager->persist($marvin);

        // — Lou47 — vendeur vêtements & cordes —
        $lou = new User();
        $lou->setEmail('lou@boxe.fr');
        $lou->setPseudo('Lou47');
        $lou->setTailleCm(171);
        $lou->setPoidsKg(65);
        $lou->setNiveau('Loisir');
        $lou->setTypeBoxe('Muay Thai');
        $lou->setBudgetMax('200');
        $lou->setDateInscription(new \DateTime('-60 days'));
        $lou->setPassword($this->hasher->hashPassword($lou, 'password123'));
        $manager->persist($lou);

        // — Tyson24 — vendeur sacs & matériel lourd —
        $tyson = new User();
        $tyson->setEmail('tyson@boxe.fr');
        $tyson->setPseudo('Tyson24');
        $tyson->setTailleCm(188);
        $tyson->setPoidsKg(90);
        $tyson->setNiveau('Compétition');
        $tyson->setTypeBoxe('Boxe Anglaise');
        $tyson->setBudgetMax('800');
        $tyson->setDateInscription(new \DateTime('-180 days'));
        $tyson->setPassword($this->hasher->hashPassword($tyson, 'password123'));
        $manager->persist($tyson);

        // — Marie90 — vendeuse équipements femme —
        $marie = new User();
        $marie->setEmail('marie@boxe.fr');
        $marie->setPseudo('Marie90');
        $marie->setTailleCm(165);
        $marie->setPoidsKg(58);
        $marie->setNiveau('Loisir');
        $marie->setTypeBoxe('Boxe Anglaise');
        $marie->setBudgetMax('250');
        $marie->setDateInscription(new \DateTime('-45 days'));
        $marie->setPassword($this->hasher->hashPassword($marie, 'password123'));
        $manager->persist($marie);

        // — Admin —
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

        // ═══════════════════════════════════════════════════
        //  ARTICLES — seconde main uniquement (pas de protège-dents)
        // ═══════════════════════════════════════════════════

        $articles = [];

        // ── GANTS ────────────────────────────────────────

        $a = new Article();
        $a->setCategorie('Gants');
        $a->setMarque('Venum');
        $a->setTaille('12 oz');
        $a->setEtat('Neuf');
        $a->setPrix('45.00');
        $a->setDescription('Gants Venum Contender 12oz, portés 3 fois seulement. Couleur noir/rouge, fermeture velcro. Idéal débutant.');
        $a->setDatePublication(new \DateTime('-5 days'));
        $a->setVendeur($jayson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['gants_venum_jayson'] = $a;

        $a = new Article();
        $a->setCategorie('Gants');
        $a->setMarque('Fairtex');
        $a->setTaille('14 oz');
        $a->setEtat('Bon état');
        $a->setPrix('70.00');
        $a->setDescription('Gants Fairtex BGV1 14oz, cuir véritable. Utilisés environ 20 séances, encore beaucoup de vie. Noir.');
        $a->setDatePublication(new \DateTime('-12 days'));
        $a->setVendeur($marvin);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['gants_fairtex_marvin'] = $a;

        $a = new Article();
        $a->setCategorie('Gants');
        $a->setMarque('Cleto Reyes');
        $a->setTaille('10 oz');
        $a->setEtat('Très bon état');
        $a->setPrix('110.00');
        $a->setDescription('Gants Cleto Reyes entraînement 10oz. Cuir haut de gamme, peu utilisés. Couleur rouge.');
        $a->setDatePublication(new \DateTime('-3 days'));
        $a->setVendeur($tyson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['gants_reyes_tyson'] = $a;

        $a = new Article();
        $a->setCategorie('Gants');
        $a->setMarque('Adidas');
        $a->setTaille('12 oz');
        $a->setEtat('Bon état');
        $a->setPrix('30.00');
        $a->setDescription('Gants Adidas Speed 50, 12oz. Synthétique, utilisés régulièrement pendant 6 mois. Bleu.');
        $a->setDatePublication(new \DateTime('-20 days'));
        $a->setVendeur($marie);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['gants_adidas_marie'] = $a;

        // ── CASQUES ───────────────────────────────────────

        $a = new Article();
        $a->setCategorie('Casques');
        $a->setMarque('Venum');
        $a->setTaille('M');
        $a->setEtat('Très bon état');
        $a->setPrix('55.00');
        $a->setDescription('Casque Venum Elite Headgear taille M. Protection joues et menton, cuir synthétique. Utilisé pour sparring léger.');
        $a->setDatePublication(new \DateTime('-8 days'));
        $a->setVendeur($jayson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['casque_venum_jayson'] = $a;

        $a = new Article();
        $a->setCategorie('Casques');
        $a->setMarque('Ringside');
        $a->setTaille('L');
        $a->setEtat('Bon état');
        $a->setPrix('40.00');
        $a->setDescription('Casque Ringside Competition taille L, avec barette nasale. Parfait pour la compétition amateur.');
        $a->setDatePublication(new \DateTime('-25 days'));
        $a->setVendeur($marvin);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['casque_ringside_marvin'] = $a;

        $a = new Article();
        $a->setCategorie('Casques');
        $a->setMarque('Fairtex');
        $a->setTaille('M');
        $a->setEtat('Excellent état');
        $a->setPrix('130.00');
        $a->setDescription('Casque Fairtex HG13 taille M. Cuir véritable, utilisé 2 fois seulement. Idéal pour sparring intensif.');
        $a->setDatePublication(new \DateTime('-2 days'));
        $a->setVendeur($tyson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['casque_fairtex_tyson'] = $a;

        // ── SACS DE FRAPPE ────────────────────────────────

        $a = new Article();
        $a->setCategorie('Sacs de frappe');
        $a->setMarque('Everlast');
        $a->setTaille('100 cm');
        $a->setEtat('Bon état');
        $a->setPrix('80.00');
        $a->setDescription('Sac de frappe Everlast 100cm, rempli de textile. Fixation plafond incluse. Quelques traces d\'usage cosmétiques.');
        $a->setDatePublication(new \DateTime('-14 days'));
        $a->setVendeur($tyson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['sac_everlast_tyson'] = $a;

        $a = new Article();
        $a->setCategorie('Sacs de frappe');
        $a->setMarque('Kwon');
        $a->setTaille('120 cm');
        $a->setEtat('Très bon état');
        $a->setPrix('95.00');
        $a->setDescription('Sac Kwon 120cm, suspension chaîne incluse. Très peu utilisé, stocké en garage. Cuir synthétique noir.');
        $a->setDatePublication(new \DateTime('-6 days'));
        $a->setVendeur($marvin);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['sac_kwon_marvin'] = $a;

        // ── BANDES & WRAPS ────────────────────────────────

        $a = new Article();
        $a->setCategorie('Bandes');
        $a->setMarque('Ringside');
        $a->setTaille('4,5 m');
        $a->setEtat('Bon état');
        $a->setPrix('8.00');
        $a->setDescription('Paire de bandes Ringside 4,5m. Lavées et entretenues. Couleur rouge.');
        $a->setDatePublication(new \DateTime('-18 days'));
        $a->setVendeur($lou);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['bandes_ringside_lou'] = $a;

        $a = new Article();
        $a->setCategorie('Bandes');
        $a->setMarque('Venum');
        $a->setTaille('4 m');
        $a->setEtat('Très bon état');
        $a->setPrix('10.00');
        $a->setDescription('Bandes Venum Kontact 4m, utilisées une dizaine de fois. Couleur noire, très bonne élasticité.');
        $a->setDatePublication(new \DateTime('-9 days'));
        $a->setVendeur($marie);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['bandes_venum_marie'] = $a;

        // ── CORDES À SAUTER ───────────────────────────────

        $a = new Article();
        $a->setCategorie('Cordes à sauter');
        $a->setMarque('RDX');
        $a->setTaille('3 m');
        $a->setEtat('Bon état');
        $a->setPrix('12.00');
        $a->setDescription('Corde à sauter RDX 3m ajustable, câble acier gainé. Parfaite pour le cardio boxe. Quelques mois d\'utilisation.');
        $a->setDatePublication(new \DateTime('-30 days'));
        $a->setVendeur($tyson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['corde_rdx_tyson'] = $a;

        $a = new Article();
        $a->setCategorie('Cordes à sauter');
        $a->setMarque('Everlast');
        $a->setTaille('2,9 m');
        $a->setEtat('Bon état');
        $a->setPrix('9.00');
        $a->setDescription('Corde à sauter Everlast, poignées confort. Très bonne pour cardio. Légèrement usée aux extrémités.');
        $a->setDatePublication(new \DateTime('-40 days'));
        $a->setVendeur($marie);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['corde_everlast_marie'] = $a;

        // ── PAOS / MITAINES ───────────────────────────────

        $a = new Article();
        $a->setCategorie('Paos');
        $a->setMarque('Twins');
        $a->setTaille('Adulte');
        $a->setEtat('Bon état');
        $a->setPrix('35.00');
        $a->setDescription('Paire de paos Twins, cuir véritable. Utilisés pour entraînement Muay Thai, encore beaucoup d\'épaisseur.');
        $a->setDatePublication(new \DateTime('-11 days'));
        $a->setVendeur($lou);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['paos_twins_lou'] = $a;

        $a = new Article();
        $a->setCategorie('Mitaines');
        $a->setMarque('Fairtex');
        $a->setTaille('Adulte');
        $a->setEtat('Très bon état');
        $a->setPrix('50.00');
        $a->setDescription('Mitaines Fairtex FMV9, cuir véritable. Peu utilisées, excellent amorti. Couleur noir/or.');
        $a->setDatePublication(new \DateTime('-4 days'));
        $a->setVendeur($jayson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['mitaines_fairtex_jayson'] = $a;

        // ── SHORTS & VÊTEMENTS ───────────────────────────

        $a = new Article();
        $a->setCategorie('Shorts');
        $a->setMarque('Venum');
        $a->setTaille('M');
        $a->setEtat('Très bon état');
        $a->setPrix('22.00');
        $a->setDescription('Short de boxe Venum Bangkok Inferno taille M. Porté quelques entraînements, lavé. Couleur rouge/noir.');
        $a->setDatePublication(new \DateTime('-7 days'));
        $a->setVendeur($lou);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['short_venum_lou'] = $a;

        $a = new Article();
        $a->setCategorie('Shorts');
        $a->setMarque('Fairtex');
        $a->setTaille('L');
        $a->setEtat('Bon état');
        $a->setPrix('28.00');
        $a->setDescription('Short Fairtex BS0644 taille L, satin léger. Excellent pour Muay Thai. Quelques lavages.');
        $a->setDatePublication(new \DateTime('-16 days'));
        $a->setVendeur($marvin);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['short_fairtex_marvin'] = $a;

        // ── ARTICLES VENDUS ───────────────────────────────

        $a = new Article();
        $a->setCategorie('Gants');
        $a->setMarque('Hayabusa');
        $a->setTaille('16 oz');
        $a->setEtat('Bon état');
        $a->setPrix('95.00');
        $a->setDescription('Gants Hayabusa T3 16oz, cuir véritable. Double fermeture velcro. Quelques séances de sparring.');
        $a->setDatePublication(new \DateTime('-45 days'));
        $a->setVendeur($tyson);
        $a->setStatut($statuts['Vendu']);
        $manager->persist($a);
        $articles['gants_hayabusa_vendu'] = $a;

        $a = new Article();
        $a->setCategorie('Casques');
        $a->setMarque('Adidas');
        $a->setTaille('S');
        $a->setEtat('Bon état');
        $a->setPrix('38.00');
        $a->setDescription('Casque Adidas Rookie taille S. Ancien modèle, légèrement dépassé mais en bon état.');
        $a->setDatePublication(new \DateTime('-50 days'));
        $a->setVendeur($marie);
        $a->setStatut($statuts['Vendu']);
        $manager->persist($a);
        $articles['casque_adidas_vendu'] = $a;

        // ── CHAUSSURES ────────────────────────────────────
        $a = new Article();
        $a->setCategorie('Chaussures');
        $a->setMarque('Adidas');
        $a->setTaille('42');
        $a->setEtat('Très bon état');
        $a->setPrix('65.00');
        $a->setDescription('Chaussures de boxe Adidas Box Hog 2, taille 42. Très légères et confortables. Portées une dizaine de fois en salle uniquement.');
        $a->setDatePublication(new \DateTime('-4 days'));
        $a->setVendeur($marie);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['chaussures_adidas_marie'] = $a;

        $a = new Article();
        $a->setCategorie('Chaussures');
        $a->setMarque('Nike');
        $a->setTaille('44');
        $a->setEtat('Bon état');
        $a->setPrix('110.00');
        $a->setDescription('Chaussures Nike Hyperko, taille 44. Excellente tenue de cheville. Quelques traces d\'usure normale.');
        $a->setDatePublication(new \DateTime('-10 days'));
        $a->setVendeur($jayson);
        $a->setStatut($statuts['En vente']);
        $manager->persist($a);
        $articles['chaussures_nike_jayson'] = $a;

        // Associer les images correspondantes aux articles
        foreach ($articles as $article) {
            $photo = new Photo();
            $photo->setNomFichier(match ($article->getCategorie()) {
                'Gants' => 'Gants.webp',
                'Casques' => 'casque de boxe.webp',
                'Sacs de frappe' => 'sac de frappe.webp',
                'Shorts' => 'short de boxe.webp',
                'Mitaines', 'Paos' => 'mitaine boxe.jpg',
                'Chaussures' => 'chaussures boxe.jpg',
                default => 'mitaine boxe.jpg', // Fallback pour Bandes et Cordes
            });
            $photo->setEstPrincipale(true);
            $photo->setArticle($article);
            $photo->setUpdatedAt(new \DateTime('-' . rand(2, 30) . ' days'));
            $manager->persist($photo);
        }

        // Flush pour avoir les IDs disponibles
        $manager->flush();

        // ═══════════════════════════════════════════════════
        //  MESSAGES DE TEST — conversations pour la messagerie
        //
        //  Toutes les conversations impliquent "test@boxe.fr"
        //  comme acheteur, afin de tester la page /messages
        // ═══════════════════════════════════════════════════

        // ──────────────────────────────────────────────────
        //  Conversation 1 : test ↔ Jayson51
        //  Sujet : Gants Venum 12oz
        //  → Message simple + offre (en attente)
        // ──────────────────────────────────────────────────
        $article = $articles['gants_venum_jayson'];

        $m = new Messagerie();
        $m->setContenu('Salut ! Les gants sont encore disponibles ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-2 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($jayson);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Oui toujours dispo ! Tu veux les voir en photo rapprochée ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-2 days +2 hours'));
        $m->setExpediteur($jayson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Non ça va, je te propose 38 €, c\'est bon pour toi ?');
        $m->setEstOffre(true);
        $m->setMontantOffre('38.00');
        $m->setStatutOffre('en attente');
        $m->setDateEnvoie(new \DateTime('-1 day'));
        $m->setExpediteur($test);
        $m->setDestinataire($jayson);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 2 : test ↔ Marvin32
        //  Sujet : Casque Ringside
        //  → Offre acceptée
        // ──────────────────────────────────────────────────
        $article = $articles['casque_ringside_marvin'];

        $m = new Messagerie();
        $m->setContenu('Bonjour, le casque est en quelle taille exactement ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-14 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($marvin);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Salut, c\'est un L, mais il peut convenir à une tête M/L. Très confortable.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-14 days +1 hour'));
        $m->setExpediteur($marvin);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('OK, je peux en proposer 32 € ?');
        $m->setEstOffre(true);
        $m->setMontantOffre('32.00');
        $m->setStatutOffre('accepte');
        $m->setDateEnvoie(new \DateTime('-13 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($marvin);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('C\'est bon pour moi ! On fait comment pour l\'envoi ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-13 days +30 minutes'));
        $m->setExpediteur($marvin);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 3 : test ↔ Lou47
        //  Sujet : Bandes Ringside
        //  → Offre refusée + contre-proposition
        // ──────────────────────────────────────────────────
        $article = $articles['bandes_ringside_lou'];

        $m = new Messagerie();
        $m->setContenu('Salut Lou, les bandes ont quelle longueur ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-21 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($lou);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Coucou ! 4,5 mètres, pratiques pour wrapping complet.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-21 days +45 minutes'));
        $m->setExpediteur($lou);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Je t\'en propose 5 €, c\'est correct non ?');
        $m->setEstOffre(true);
        $m->setMontantOffre('5.00');
        $m->setStatutOffre('refuse');
        $m->setDateEnvoie(new \DateTime('-20 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($lou);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Désolé, je peux pas descendre en dessous de 7 € avec les frais d\'envoi...');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-20 days +10 minutes'));
        $m->setExpediteur($lou);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 4 : test ↔ Tyson24
        //  Sujet : Corde à sauter RDX
        //  → Simple échange de messages
        // ──────────────────────────────────────────────────
        $article = $articles['corde_rdx_tyson'];

        $m = new Messagerie();
        $m->setContenu('Hey Tyson ! La corde est ajustable à partir de quelle taille ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-31 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($tyson);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Elle fait 3m brut, s\'ajuste jusqu\'à 2,4m. Compatible taille jusqu\'à 1,90m.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-31 days +2 hours'));
        $m->setExpediteur($tyson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Parfait. Est-ce que tu fais Mondial Relay ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-30 days'));
        $m->setExpediteur($test);
        $m->setDestinataire($tyson);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 5 : test ↔ Marie90
        //  Sujet : Gants Adidas
        //  → Offre en attente (récente)
        // ──────────────────────────────────────────────────
        $article = $articles['gants_adidas_marie'];

        $m = new Messagerie();
        $m->setContenu('Bonjour Marie, les gants ont une odeur ? (cuir ou synthétique ?)');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-1 month'));
        $m->setExpediteur($test);
        $m->setDestinataire($marie);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Synthétique, pas d\'odeur, ils ont été régulièrement aérés et désodorisés.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-1 month +3 hours'));
        $m->setExpediteur($marie);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Super. Je propose 24 €, tu penses quoi ?');
        $m->setEstOffre(true);
        $m->setMontantOffre('24.00');
        $m->setStatutOffre('en attente');
        $m->setDateEnvoie(new \DateTime('-1 month +4 hours'));
        $m->setExpediteur($test);
        $m->setDestinataire($marie);
        $m->setArticle($article);
        $manager->persist($m);

        // ══════════════════════════════════════════════════════════════════
        //  CONVERSATIONS INVERSÉES — test@boxe.fr REÇOIT des offres
        //  (pour tester les boutons Accepter / Refuser)
        // ══════════════════════════════════════════════════════════════════

        // ──────────────────────────────────────────────────
        //  Conversation 6 : Jayson → test
        //  Sujet : Mitaines Fairtex (vendues par Jayson)
        //  → Jayson envoie une offre à test (qui voudrait acheter)
        //  → statut : en attente → test peut accepter ou refuser
        // ──────────────────────────────────────────────────
        $article = $articles['mitaines_fairtex_jayson'];

        $m = new Messagerie();
        $m->setContenu('Salut TestBoxer, j\'ai vu ton profil, je pense que mes mitaines Fairtex pourraient t\'intéresser !');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-3 days'));
        $m->setExpediteur($jayson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Elles sont en très bon état, je peux te les laisser pour 40 € au lieu de 50 €.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-3 days +30 minutes'));
        $m->setExpediteur($jayson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('C\'est bon pour toi ? Je te fais l\'offre officielle :');
        $m->setEstOffre(true);
        $m->setMontantOffre('40.00');
        $m->setStatutOffre('en attente');
        $m->setDateEnvoie(new \DateTime('-3 days +1 hour'));
        $m->setExpediteur($jayson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 7 : Marvin → test
        //  Sujet : Sac Kwon 120cm (vendu par Marvin)
        //  → Marvin propose une réduction à test
        //  → statut : accepte (pour montrer le flux complet)
        // ──────────────────────────────────────────────────
        $article = $articles['sac_kwon_marvin'];

        $m = new Messagerie();
        $m->setContenu('Hey, je suis Marvin32. Je vends mon sac Kwon, encore nickel. Interested ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-10 days'));
        $m->setExpediteur($marvin);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Ouais ça m\'intéresse, c\'est livrable ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-10 days +2 hours'));
        $m->setExpediteur($test);
        $m->setDestinataire($marvin);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Oui je fais Colissimo. Je te le laisse à 80 € port inclus, c\'est une bonne affaire !');
        $m->setEstOffre(true);
        $m->setMontantOffre('80.00');
        $m->setStatutOffre('accepte');
        $m->setDateEnvoie(new \DateTime('-9 days'));
        $m->setExpediteur($marvin);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Top, j\'accepte ! Tu peux m\'envoyer ton RIB ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-9 days +15 minutes'));
        $m->setExpediteur($test);
        $m->setDestinataire($marvin);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 8 : Tyson → test
        //  Sujet : Casque Fairtex (vendu par Tyson)
        //  → Tyson propose un prix, test refuse
        //  → statut : refuse
        // ──────────────────────────────────────────────────
        $article = $articles['casque_fairtex_tyson'];

        $m = new Messagerie();
        $m->setContenu('Bonjour TestBoxer ! Mon casque Fairtex t\'intéresse ? Je suis ouvert à la négo.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-5 days'));
        $m->setExpediteur($tyson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Hmm, c\'est un peu cher pour moi... t\'es à combien minimum ?');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-5 days +1 hour'));
        $m->setExpediteur($test);
        $m->setDestinataire($tyson);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Je peux descendre à 110 €, c\'est mon dernier mot, cuir véritable Fairtex !');
        $m->setEstOffre(true);
        $m->setMontantOffre('110.00');
        $m->setStatutOffre('refuse');
        $m->setDateEnvoie(new \DateTime('-4 days'));
        $m->setExpediteur($tyson);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Désolé, c\'est encore trop pour mon budget. Bonne chance pour la vente !');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-4 days +5 minutes'));
        $m->setExpediteur($test);
        $m->setDestinataire($tyson);
        $m->setArticle($article);
        $manager->persist($m);

        // ──────────────────────────────────────────────────
        //  Conversation 9 : Lou → test
        //  Sujet : Paos Twins (vendus par Lou)
        //  → Lou fait une offre à test
        //  → statut : en attente (récente, test peut encore répondre)
        // ──────────────────────────────────────────────────
        $article = $articles['paos_twins_lou'];

        $m = new Messagerie();
        $m->setContenu('Salut ! Tu pratiques le Muay Thai ? J\'ai des paos Twins en super état.');
        $m->setEstOffre(false);
        $m->setDateEnvoie(new \DateTime('-1 day'));
        $m->setExpediteur($lou);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $m = new Messagerie();
        $m->setContenu('Je te propose 28 € au lieu de 35 €, livraison Mondial Relay offerte !');
        $m->setEstOffre(true);
        $m->setMontantOffre('28.00');
        $m->setStatutOffre('en attente');
        $m->setDateEnvoie(new \DateTime('-1 day +2 hours'));
        $m->setExpediteur($lou);
        $m->setDestinataire($test);
        $m->setArticle($article);
        $manager->persist($m);

        $manager->flush();
    }
}
