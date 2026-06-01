# 🥊 Contexte Produit & Business : Second Round

## 📖 Vision Générale
**Second Round** n'est pas qu'une simple marketplace C2C. C'est une plateforme **solidaire, écologique et pédagogique** dédiée à la boxe et aux sports de combat. 
Le constat est simple : la boxe attire des publics variés, mais l'équipement coûte cher. Les débutants se tournent souvent vers du matériel inadapté ou dangereux par manque de budget, tandis que d'autres (comme les parents de jeunes pratiquants) accumulent du matériel neuf inutilisé après un abandon rapide. 

L'objectif est de donner une **seconde vie (un second round)** au matériel, de lutter contre le gaspillage et de rendre ce sport accessible à tous en toute sécurité.

## 🎯 Public Cible (Personas)
L'application s'adresse à trois profils principaux :
1. **L'étudiant / Petit budget (ex: Lucas, 22 ans) :** Cherche du matériel fiable d'occasion pour s'équiper sans se ruiner. A besoin d'être rassuré sur la qualité.
2. **Le parent (ex: Karim, 43 ans) :** A acheté du matériel neuf pour son enfant qui a vite abandonné. Il cherche une solution simple et responsable pour s'en débarrasser sans jeter.
3. **Le jeune actif pressé (ex: Sarah, 29 ans) :** Cherche l'efficacité. Veut un parcours d'achat clair, rapide et guidé pour ne pas perdre de temps.

## 💡 Fonctionnalités Clés (Côté Utilisateur)
Basé sur les maquettes et l'étude UX/UI, voici les fonctionnalités qui définissent l'expérience de Second Round :

### 1. Le "Vestiaire" (Profil Utilisateur Avancé)
L'utilisateur ne fait pas que vendre, il expose son profil de sportif.
- **Données morphologiques & sportives :** Poids, taille, type de boxe (ex: Boxe Anglaise), niveau (Loisir, Compétition).
- **Inventaire :** Liste des articles en vente par l'utilisateur.
- **Réputation :** Système d'évaluations (étoiles) et commentaires laissés par les autres membres.

### 2. Dimension Pédagogique (Les Guides)
L'application guide les néophytes pour éviter les erreurs d'achat.
- **Guide des tailles :** Tableaux liant le poids du boxeur à la taille des gants (oz) selon l'usage (entraînement vs sparring).
- **Guide des équipements :** Explications sur l'utilité des casques, bandes, protège-dents, etc.

### 3. Les "Packs" (Achat groupé & Recommandation)
Pour faciliter la vie des débutants (et répondre au persona pressé), l'application propose des lots.
- **Packs prédéfinis :** Pack Enfant, Pack Loisir, Pack Compétition (ex: Gants + Bandes + Protège-dents).
- **Pack Personnalisé :** L'algorithme propose un pack d'articles d'occasion basés sur le budget et les mensurations de l'acheteur.

### 4. La Revente Simplifiée (Marketplace)
- Parcours de mise en vente guidé étape par étape (façon "Enchaînement" : Garde -> Gauche -> Droite -> Uppercut).
- Catégories précises (Gants, Casques, Bandes, Vêtements, Sacs).

### 5. Messagerie & Négociation
- Système de discussion intégré pour poser des questions sur l'état du matériel.
- Possibilité de faire et d'accepter des offres de prix directement dans le chat.

## ⚙️ Stack Technique & Architecture de Données Actuelle
- **Backend :** API REST propulsée par PHP 8.2, Symfony 7, et API Platform.
- **Sécurité :** Authentification JWT.
- **Base de données MySQL (Entités créées) :** `User`, `Article`, `Statut`, `Photo`, `Favori`, `Messagerie`, `Commande`, `CommentaireArticle`, `Evaluation`.
- **Frontend cible :** Application React (consommant l'API Symfony).

## 🤖 Instruction pour l'Agent IA
Ce document sert de référentiel métier. Lors de la génération de code (front ou back) :
- Adaptez le vocabulaire (utilisez "Vestiaire" pour le profil, "Round" pour les étapes).
- Prenez en compte que les recommandations d'articles doivent pouvoir se baser sur le poids/taille de l'utilisateur.
