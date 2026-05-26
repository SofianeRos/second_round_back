# 🥊 Second Round - Backend API

Une plateforme marketplace solidaire, écologique et pédagogique dédiée à la boxe et aux sports de combat. **Donnez une seconde vie au matériel de boxe !**

## 📋 Table des matières

- [À propos](#-à-propos)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Lancement](#-lancement)
- [API Documentation](#-api-documentation)
- [Utilisateurs de test](#-utilisateurs-de-test)
- [Structure du projet](#-structure-du-projet)
- [Stack technologique](#-stack-technologique)
- [Entités principales](#-entités-principales)

---

## 📖 À propos

**Second Round** est une API REST robuste construite avec **Symfony 7** et **API Platform** qui permet :

✅ Gestion complète des utilisateurs (inscription, authentification JWT)
✅ Mise en ligne d'articles (gants, casques, bandes, protections)
✅ Système de favoris et de wishlist
✅ Messagerie intégrée entre vendeurs et acheteurs
✅ Gestion des commandes et suivi de livraison
✅ Système d'évaluation et de notation
✅ Photos multi-articles

### 🎯 Publics visés

- **L'étudiant/Petit budget :** Cherche du matériel fiable d'occasion
- **Le parent :** Veut se débarrasser du matériel de son enfant responsablement
- **Le jeune actif :** Cherche l'efficacité et la rapidité

---

## 🔧 Prérequis

Avant de commencer, assurez-vous d'avoir :

- **PHP 8.2+** avec extensions : `ext-ctype`, `ext-iconv`, `ext-json`
- **Composer** (gestion des dépendances PHP)
- **Docker & Docker Compose** (pour la base de données MySQL)
- **Git** (pour le versioning)

Vérifiez votre installation :

```bash
php --version          # PHP 8.2+
composer --version     # 2.x
docker --version       # 20.x+
docker-compose --version # 1.29+
```

---

## 📦 Installation

### 1. Cloner le dépôt

```bash
git clone <url-du-repo>
cd second_round_back
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

```bash
# Copier le fichier d'environnement
cp .env .env.local
```

Modifiez `.env.local` si nécessaire (par défaut, les valeurs sont pré-configurées) :

```env
DATABASE_URL="mysql://app:secret@mysql:3306/second_round"
JWT_SECRET_KEY="%kernel.project_dir%/config/jwt/private.pem"
JWT_PUBLIC_KEY="%kernel.project_dir%/config/jwt/public.pem"
JWT_PASSPHRASE="your_jwt_passphrase"
```

### 4. Générer les clés JWT

```bash
php bin/console lexik:jwt:generate-keypair
```

---

## 🚀 Configuration

### 1. Lancer les conteneurs Docker

```bash
docker-compose up -d
```

Vérifiez que MySQL est bien démarré :

```bash
docker-compose ps
```

### 2. Créer la base de données

```bash
php bin/console doctrine:database:create
```

### 3. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

### 4. Charger les fixtures (données de test)

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

Cela va créer :

- 4 utilisateurs de test
- Plusieurs articles de test
- Des statuts de vente

---

## 🎮 Lancement

### Démarrer le serveur de développement Symfony

```bash
php -S localhost:8000 -t public
```

L'API sera accessible à : **http://localhost:8000**

### Accéder à la documentation API

Une documentation interactive Swagger/OpenAPI est disponible à :
**http://localhost:8000/api/docs**

---

## 📡 API Documentation

### Endpoints principaux

#### 🔐 Authentification

```
POST /api/login_check
Content-Type: application/json

{
  "email": "admin@boxe.fr",
  "password": "admin123"
}
```

Retourne : `{ "token": "eyJ..." }`

#### 👥 Utilisateurs

```
GET    /api/users              # Lister tous les utilisateurs
POST   /api/users              # Créer un nouvel utilisateur
GET    /api/users/{id}         # Récupérer un utilisateur
PUT    /api/users/{id}         # Mettre à jour un utilisateur
DELETE /api/users/{id}         # Supprimer un utilisateur
```

#### 📦 Articles

```
GET    /api/articles           # Lister tous les articles
POST   /api/articles           # Créer un nouvel article (authentifié)
GET    /api/articles/{id}      # Récupérer un article
PUT    /api/articles/{id}      # Mettre à jour un article (authentifié)
DELETE /api/articles/{id}      # Supprimer un article (authentifié)
```

#### ❤️ Favoris

```
GET    /api/favoris            # Lister les favoris
POST   /api/favoris            # Ajouter un favori
DELETE /api/favoris/{id}       # Retirer un favori
```

#### 💬 Messagerie

```
GET    /api/messageries        # Lister les messages
POST   /api/messageries        # Créer un nouveau message
GET    /api/messageries/{id}   # Récupérer un message
```

#### 📊 Commandes

```
GET    /api/commandes          # Lister les commandes
POST   /api/commandes          # Créer une commande
GET    /api/commandes/{id}     # Récupérer une commande
PUT    /api/commandes/{id}     # Mettre à jour une commande
```

#### ⭐ Évaluations

```
GET    /api/evaluations        # Lister les évaluations
POST   /api/evaluations        # Créer une évaluation
GET    /api/evaluations/{id}   # Récupérer une évaluation
```

---

## 👤 Utilisateurs de test

La fixture charge automatiquement 4 utilisateurs :

| Email              | Pseudo        | Mot de passe | Rôle  | Niveau      |
| ------------------ | ------------- | ------------ | ----- | ----------- |
| `lucas@example.fr` | LucasBoxeur   | password123  | USER  | Loisir      |
| `sarah@example.fr` | SarahChampion | password456  | USER  | Compétition |
| `test@boxe.fr`     | TestBoxer     | azerty       | USER  | Loisir      |
| `admin@boxe.fr`    | AdminBoxe     | admin123     | ADMIN | Compétition |

### Exemple de connexion avec cURL

```bash
curl -X POST http://localhost:8000/api/login_check \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@boxe.fr",
    "password": "admin123"
  }'
```

Réponse :

```json
{
    "token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

### Utiliser le token pour une requête authentifiée

```bash
curl -H "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..." \
  http://localhost:8000/api/users
```

---

## 🗂️ Structure du projet

```
second_round_back/
├── bin/
│   └── console                 # CLI Symfony
├── config/
│   ├── bundles.php            # Configuration des bundles
│   ├── packages/              # Configuration des paquets
│   │   ├── api_platform.yaml
│   │   ├── security.yaml
│   │   ├── lexik_jwt_authentication.yaml
│   │   └── nelmio_cors.yaml
│   ├── routes/                # Définition des routes
│   ├── jwt/                   # Clés JWT
│   │   ├── private.pem
│   │   └── public.pem
│   ├── services.yaml          # Configuration des services
│   └── routes.yaml
├── migrations/                # Migrations Doctrine
├── public/
│   ├── index.php             # Point d'entrée de l'API
│   └── images/               # Stockage des photos
├── src/
│   ├── Kernel.php
│   ├── Entity/               # Entités Doctrine
│   │   ├── User.php
│   │   ├── Article.php
│   │   ├── Photo.php
│   │   ├── Messagerie.php
│   │   ├── Commande.php
│   │   ├── Favori.php
│   │   ├── CommentaireArticle.php
│   │   ├── Evaluation.php
│   │   └── Statut.php
│   ├── Repository/           # Requêtes personnalisées
│   ├── Controller/           # Controllers personnalisés
│   ├── DataFixtures/         # Données de test
│   ├── EventListener/        # Listeners d'événements
│   ├── State/                # Processors API Platform
│   └── Security/             # Logique de sécurité
├── templates/                # Templates Twig (si nécessaire)
├── var/
│   └── cache/               # Cache applicatif
├── .env                     # Variables d'environnement (ne pas commiter)
├── .env.local              # Surcharges locales
├── composer.json           # Dépendances PHP
├── docker-compose.yaml    # Orchestration des conteneurs
└── symfony.lock           # Lock file des dépendances
```

---

## 🛠️ Stack technologique

| Technologie       | Version | Usage                      |
| ----------------- | ------- | -------------------------- |
| **PHP**           | 8.2+    | Langage backend            |
| **Symfony**       | 7.4     | Framework web              |
| **API Platform**  | 4.3     | Framework API REST         |
| **Doctrine ORM**  | 3.6     | ORM/Mapper d'objets        |
| **MySQL**         | 5.7+    | Base de données            |
| **Docker**        | 20.x+   | Conteneurisation           |
| **LexikJWT**      | 3.2     | Authentification JWT       |
| **CORS**          | 2.6     | Gestion CORS               |
| **Vich Uploader** | -       | Gestion de fichiers/photos |

---

## 📊 Entités principales

### User

Utilisateur de la plateforme (vendeur ou acheteur).

```
- id : int (clé primaire)
- email : string (unique)
- password : string (hashé)
- pseudo : string
- taille_cm : int
- poids_kg : int
- niveau : enum (Loisir, Compétition)
- type_boxe : string
- budget_max : decimal
- roles : json (ROLE_USER, ROLE_ADMIN)
- date_inscription : datetime
- relations : articles (vendeur), commandes (acheteur), messageries, favoris...
```

### Article

Un article à vendre (gants, casque, bandes, etc.).

```
- id : int (clé primaire)
- categorie : string
- marque : string
- taille : string
- etat : string
- prix : decimal
- description : text
- date_publication : datetime
- vendeur : User (ManyToOne)
- statut : Statut (ManyToOne)
- photos : Collection<Photo>
- commentaires : Collection<CommentaireArticle>
```

### Statut

Référence pour l'état d'un article.

```
- id : int (clé primaire)
- libelle : string (En vente, Vendu, Réservé, Retiré)
- description : string
- couleur_badge : string
- par_defaut : boolean
```

### Photo

Photo d'un article.

```
- id : int (clé primaire)
- article : Article (ManyToOne)
- url : string
- date_upload : datetime
```

### Messagerie

Conversation entre vendeur et acheteur.

```
- id : int (clé primaire)
- contenu : text
- expediteur : User (ManyToOne)
- destinataire : User (ManyToOne)
- date_envoi : datetime
- offre_prix : decimal (nullable)
```

### Commande

Enregistrement d'une transaction.

```
- id : int (clé primaire)
- acheteur : User (ManyToOne)
- article : Article (ManyToOne)
- montant_total : decimal
- statut_livraison : string
- date_commande : datetime
```

### Favori

Marquage d'articles favoris.

```
- id : int (clé primaire)
- user : User (ManyToOne)
- article : Article (ManyToOne)
- date_ajout : datetime
```

---

## 🐛 Dépannage

### Le conteneur MySQL ne démarre pas

```bash
# Vérifier les logs Docker
docker-compose logs mysql

# Redémarrer le conteneur
docker-compose restart mysql
```

### Erreur de connexion à la base de données

```bash
# Vérifier que .env.local contient la bonne DATABASE_URL
cat .env.local | grep DATABASE_URL

# Recréer la base de données
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### JWT ne fonctionne pas

```bash
# Régénérer les clés JWT
php bin/console lexik:jwt:generate-keypair

# Vérifier les permissions
ls -la config/jwt/
```

### Erreur "Access Denied"

- Vérifiez que vous envoyez le token JWT dans le header `Authorization: Bearer <token>`
- Vérifiez que l'utilisateur a les rôles nécessaires

---

## 📝 Commandes utiles

```bash
# Lister toutes les routes
php bin/console debug:router

# Tester une requête API
php bin/console debug:event-dispatcher

# Vider le cache
php bin/console cache:clear

# Générer une entité
php bin/console make:entity

# Générer une migration
php bin/console make:migration

# Vérifier les migrations non exécutées
php bin/console doctrine:migrations:status
```

---

## 🔒 Sécurité

### Authentification

- JWT (JSON Web Token) avec signature RS256
- Clés privée/publique en `config/jwt/`
- Expiration des tokens configurable

### CORS

La configuration CORS permet les requêtes depuis :

- `localhost:3000` (développement React)
- `localhost:8000` (développement Symfony)

### Rôles

- `ROLE_USER` : Utilisateur standard
- `ROLE_ADMIN` : Accès administrateur
- `ROLE_VENDEUR` : Vendeur vérifié (futur)

---

## 📞 Support & Contact

Pour toute question ou bug, consultez :

- [Documentation Symfony](https://symfony.com/doc)
- [Documentation API Platform](https://api-platform.com)
- [Documentation Doctrine ORM](https://www.doctrine-project.org)

---

**Bon développement ! 🚀 Donnez une seconde vie au matériel de boxe ! 🥊**
