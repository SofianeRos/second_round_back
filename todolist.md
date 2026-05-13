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

## 🔵 Phase 3 : Pagination, Filtres & Améliorations

- [ ] **Pagination API** :
    - [ ] Configurer `itemsPerPage` par défaut (ex: 20 articles par page)
    - [ ] Tester `/api/articles?page=1`
- [ ] **Filtres de recherche** :
    - [ ] Filtre par prix (min/max)
    - [ ] Filtre par catégorie
    - [ ] Filtre par statut
    - [ ] Filtre par vendeur
    - [ ] Recherche texte sur la description/marque
- [ ] **Tri** :
    - [ ] Tri par prix (croissant/décroissant)
    - [ ] Tri par date de publication
- [ ] **Contrôles d'accès avancés** :
    - [ ] Voter : seul le vendeur peut modifier son article
    - [ ] Voter : seul le vendeur peut le supprimer

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
