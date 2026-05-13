# Projet : Second Round 🥊

## 📋 Présentation du Projet
**Second Round** est une plateforme marketplace (C2C) spécialisée dans le matériel de sports de combat (boxe, MMA, etc.). Le but est de permettre aux pratiquants de vendre et d'acheter du matériel d'occasion ou neuf (gants, sacs de frappe, protections) de manière sécurisée.

## 🎯 Objectifs & Cahier des Charges

### Fonctionnalités principales :
- **Gestion des utilisateurs :** Inscription, profils de boxeurs, système d'évaluation entre membres.
- **Catalogue de produits :** Publication d'annonces détaillées (catégorie, marque, taille, état, photos).
- **Messagerie intégrée :** Discussion entre acheteurs et vendeurs avec possibilité de faire des offres de prix.
- **Système de Favoris :** Permettre aux utilisateurs de sauvegarder des articles.
- **Gestion des transactions :** Suivi des commandes et des statuts de livraison.
- **API REST :** Backend totalement découplé pour être consommé par un futur Front-end React.

## 🛠️ Stack Technique
- **Backend :** PHP 8.2+ / Symfony 7.0
- **API :** API Platform (génération automatique de la documentation Swagger)
- **Base de données :** MySQL via Docker
- **Sécurité :** LexikJWTAuthenticationBundle (Authentification par jeton)

## 🏗️ Architecture des Données (Entités)
Toutes les entités suivantes ont été créées et migrées en base de données :
1. **User :** Gère l'identité, l'email, le mot de passe (haché) et les rôles.
2. **Article :** Le cœur du catalogue (prix, description, lien vers vendeur et statut).
3. **Statut :** Table de référence pour l'état de vente (En vente, Vendu, etc.).
4. **Photo :** Gestion multi-images par article.
5. **Favori :** Lien entre un utilisateur et un article.
6. **Messagerie :** Stockage des messages et des offres de prix.
7. **Commande :** Détails de la transaction finale.
8. **CommentaireArticle :** Questions/réponses publiques sur les annonces.
9. **Evaluation :** Notes et avis entre utilisateurs.

## 📈 Avancement des travaux

### ✅ Ce qui a été fait :
1. **Initialisation du projet :** Installation de Symfony et configuration de Docker.
2. **Modélisation :** Création de toutes les entités Doctrine avec leurs relations (ManyToOne, OneToMany).
3. **Exposition API :** Configuration de `#[ApiResource]` sur chaque entité pour les rendre accessibles via `/api`.
4. **Mise en place du JWT :**
   - Installation de `LexikJWTAuthenticationBundle`.
   - Génération des clés SSH pour la signature des jetons.
   - Configuration du pare-feu (`security.yaml`) et des routes de login.
5. **Fixtures :** Création d'un script `AppFixtures.php` pour générer un utilisateur de test (`test@boxe.fr` / `azerty`).

### 🚧 En cours / Blocage actuel :
Nous sommes en train de finaliser la compatibilité de l'entité **User** avec le système de sécurité de Symfony. 
- **Problème rencontré :** L'entité User initiale ne possédait pas les méthodes `setEmail()` et `setPassword()` requises pour l'authentification.
- **Solution en cours :** Utilisation de `php bin/console make:user` pour transformer l'entité en objet de sécurité et mise à jour de la base de données via les migrations.

## 🚀 Prochaines étapes
1. **Validation du Token :** Réussir à générer le premier jeton JWT via la route `/api/login_check`.
2. **Sécurisation des routes :** Restreindre l'accès à certaines opérations (ex: seul le propriétaire peut modifier son article).
3. **Serialization Groups :** Nettoyer les réponses JSON pour ne pas exposer de données sensibles.
4. **Upload d'images :** Configurer la gestion réelle des fichiers photos.