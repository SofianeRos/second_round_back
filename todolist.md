# 📝 TODO List : Projet Second Round 🥊

## ✅ Phase 1 : Finalisation Backend & Authentification (CRITIQUE)

- [x] **Réparer l'entité User** : Implémentation des interfaces de sécurité
- [x] **Synchroniser la BDD** : Migrations créées et exécutées
- [x] **Charger les Fixtures** : Utilisateurs et articles de test créés
- [x] **Valider le JWT** : Token généré et fonctionnel ✨

## ✅ Phase 2 : Nettoyage & Sécurisation de l'API

- [x] **Serialization Groups** :
    - [x] Attributs `#[Groups]` dans `User` (mot de passe caché) ✅
    - [x] Groupes dans `Article`, `Statut`, `Photo` avec détails du vendeur
- [x] **Règles de sécurité** :
    - [x] GET public, POST/PUT/PATCH/DELETE authentifiés
    - [x] Configuration `access_control` en place

## ✅ Phase 3 : Pagination, Filtres & Améliorations

- [x] **Pagination API** :
    - [x] Configurer `itemsPerPage` par défaut (ex: 20 articles par page)
    - [x] Tester `/api/articles?page=1`
- [x] **Filtres de recherche** :
    - [x] Filtre par prix (min/max)
    - [x] Filtre par catégorie
    - [x] Filtre par statut
    - [x] Filtre par vendeur
    - [x] Recherche texte sur la description/marque
- [x] **Tri** :
    - [x] Tri par prix (croissant/décroissant)
    - [x] Tri par date de publication
- [x] **Contrôles d'accès avancés** :
    - [x] Voter : seul le vendeur peut modifier son article
    - [x] Voter : seul le vendeur peut le supprimer

## 🟢 Phase 4 : Upload d'images & Assets

- [ ] **Gestion des photos** :
    - [ ] Installer `VichUploaderBundle`
    - [ ] Configurer le stockage des fichiers
    - [ ] Route pour uploader une photo sur un article
- [ ] **Thumbnails** :
    - [ ] Génération automatique de vignettes
    - [ ] Compression d'images

## 🟡 Phase 5 : Messagerie & Offres

- [ ] **Endpoint POST pour envoyer un message**
- [ ] **Endpoint pour faire une offre de prix**
- [ ] **Notifications (futur)**

## 🟣 Phase 6 : Frontend React

- [ ] Initialiser le projet React
- [ ] Pages principales : Home, Product, Login, Profile
- [ ] Intégration avec l'API Symfony
