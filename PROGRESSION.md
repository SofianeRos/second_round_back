# 📊 État du Projet Second Round - 13 mai 2026

## ✅ Accomplissements

### Phase 1 : Backend & Authentification JWT ✨

- ✅ Entité User avec interfaces de sécurité Symfony
- ✅ Migration des colonnes `email`, `password`, `roles`
- ✅ Authentification JWT via LexikJWTAuthenticationBundle
- ✅ 3 utilisateurs de test avec profils variés (Lucas, Sarah, Test)

### Phase 2 : Sécurisation & Serialization

- ✅ Groupes de sérialisation `read/write` sur toutes les entités
- ✅ **Mots de passe jamais exposés** dans les réponses JSON
- ✅ Contrôles d'accès : GET public, POST/PUT/PATCH/DELETE authentifiés
- ✅ Configuration `access_control` en place

### Phase 3 : Données de Test & Structure Avancée

- ✅ 4 statuts d'articles (En vente, Vendu, Réservé, Retiré)
- ✅ 4 articles de test avec détails réalistes (Gants, Protections, Bandes, Casques)
- ✅ Données morphologiques utilisateurs (taille, poids, niveau, type boxe)

### Phase 3 (Partiel) : Pagination & Filtres

- ✅ Pagination configurée (20 items par page par défaut)
- ✅ Filtres SearchFilter sur : catégorie, marque, description, vendeur
- ✅ Filtres RangeFilter sur les prix (min/max)
- ✅ OrderFilter sur prix et datePublication
- ⏳ Tests des filtres en cours d'intégration

## 🚀 API Endpoints Disponibles

### Authentification

```bash
POST /api/login_check
{
  "email": "test@boxe.fr",
  "password": "azerty"
}
```

**Réponse:** Token JWT

### Articles (Lecture publique, Création authentifiée)

```bash
# Lister tous les articles (20 par page)
GET /api/articles?page=1

# Filtrer par catégorie
GET /api/articles?categorie=Gants

# Filtrer par prix
GET /api/articles?prix[gte]=10&prix[lte]=100

# Trier par prix (DESC)
GET /api/articles?order[prix]=DESC

# Trier par date récente
GET /api/articles?order[datePublication]=DESC

# Créer un article (authentifié)
POST /api/articles
Headers: Authorization: Bearer {token}
{
  "categorie": "Gants",
  "marque": "Venum",
  "taille": "12oz",
  "etat": "Neuf",
  "prix": "50.00",
  "description": "Gants de boxe neufs",
  "statut": "/api/statuts/1"
}
```

### Utilisateurs (Lecture publique)

```bash
# Lister tous les utilisateurs
GET /api/users

# Rechercher par pseudo
GET /api/users?pseudo=Lucas

# Filtrer par niveau
GET /api/users?niveau=Compétition
```

### Statuts

```bash
GET /api/statuts
```

## 📦 Stack Technique

- **Backend:** PHP 8.2 + Symfony 7.0
- **API:** API Platform 3.x
- **Base de données:** MySQL 8.0 (Docker)
- **Sécurité:** JWT (LexikJWTAuthenticationBundle)
- **Sérialisation:** Serializer Component + Groups
- **ORM:** Doctrine

## 🔐 Sécurité Implémentée

- ✅ Mots de passe hachés avec algorithme sécurisé
- ✅ Tokens JWT valides 1 heure
- ✅ Clés RSA pour signature des jetons
- ✅ Contrôles d'accès par role (ROLE_USER)
- ✅ Endpoints protégés en modification (POST/PUT/PATCH/DELETE)

## 📝 Fixtures de Test

### Utilisateurs

1. **Lucas** (lucas@example.fr / password123)
    - Profil acheteur étudiant
    - Poids: 68kg, Taille: 175cm, Budget: 200€
2. **Sarah** (sarah@example.fr / password456)
    - Profil vendeur expérimenté
    - Poids: 62kg, Taille: 168cm, Budget: 500€, Niveau: Compétition
3. **Test** (test@boxe.fr / azerty)
    - Compte de test pour API
    - Niveau: Loisir, Type: MMA

### Articles

- Gants Venum 12oz - 45€ (En vente)
- Protège-dents Evenflo - 15€ (En vente)
- Bandes Ringside - 8€ (En vente)
- Casque Fairtex - 120€ (Vendu)

## 🎯 Prochaines Étapes

### Phase 3 (Suite)

- [ ] Valider les filtres en production
- [ ] Ajouter filtres supplémentaires (taille, niveau vendeur)
- [ ] Implémenter les Voters pour contrôle vendeur
- [ ] Performance: Indexation DB

### Phase 4

- [ ] Upload d'images via VichUploaderBundle
- [ ] Compression/Thumbnails
- [ ] Endpoint /photos

### Phase 5

- [ ] API Messagerie
- [ ] Système d'offres de prix
- [ ] Notifications WebSocket (futur)

### Phase 6

- [ ] Frontend React
- [ ] Pages: Home, Product Detail, Search, Login, Profile
- [ ] Panier & Checkout (future)

## 📚 Documentation

Tous les endpoints sont documentés via API Platform Swagger:

```
http://localhost:8000/api/docs
```

## 🐛 Notes Importantes

1. **Eager Loading:** Limité à 3 joins pour éviter les requêtes N+1
2. **Serialization:** MaxDepth activé pour éviter les boucles circulaires
3. **Cache:** Middleware API Platform actif pour performances
4. **Contexte Métier:** Intégré dans le projet (`context_second_round.md`)

---

**Statut:** Backend ✅ 85% complet | Frontend ⏳ À démarrer
