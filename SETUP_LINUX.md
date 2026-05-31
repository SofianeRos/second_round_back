# 🐧 Installation du Backend Second Round sur Linux

Guide complet après avoir cloné/pulé le repository sur Linux.

## 1️⃣ Prérequis

Avant de commencer, assure-toi d'avoir installé :

```bash
# Vérifier les versions
php --version          # PHP 8.2+
composer --version     # 2.x
docker --version       # 20.x+
docker-compose --version # 1.29+
```

---

## 2️⃣ Après le Pull Git

```bash
cd second_round_back
```

### Configurer Git (si ce n'est pas déjà fait)

```bash
git config core.autocrlf true
```

---

## 3️⃣ Installation des Dépendances

```bash
composer install
```

---

## 4️⃣ Sécuriser les Clés JWT

⚠️ **IMPORTANT** - Les clés JWT doivent avoir les bonnes permissions sur Linux :

```bash
chmod 600 config/jwt/private.pem
chmod 644 config/jwt/public.pem
```

Vérifier les permissions :

```bash
ls -la config/jwt/
```

Résultat attendu :

```
-rw------- ... private.pem
-rw-r--r-- ... public.pem
```

---

## 5️⃣ Configuration d'Environnement

Créer le fichier `.env.local` s'il n'existe pas :

```bash
cp .env .env.local
```

Vérifier/modifier les valeurs (selon ta config Linux) :

```bash
# .env.local
DATABASE_URL="mysql://app:secret@localhost:3306/second_round?serverVersion=8.0.32"
APP_ENV=dev
APP_SECRET=your_secret_key
```

---

## 6️⃣ Lancer Docker (MySQL)

```bash
docker-compose up -d
```

Vérifier que MySQL est bien démarré :

```bash
docker-compose ps
```

Attendre 10-15 secondes que MySQL soit prêt.

---

## 7️⃣ Base de Données

### Créer la base de données

```bash
php bin/console doctrine:database:create
```

### Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

---

## 8️⃣ Charger les Données de Test (Optionnel)

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

Cela va créer :

- 4 utilisateurs de test
- Plusieurs articles
- Données de test

---

## 9️⃣ Lancer le Serveur de Développement

```bash
php -S localhost:8000 -t public
```

L'API sera accessible sur : **http://localhost:8000**

Documentation Swagger : **http://localhost:8000/api/docs**

---

## 🔟 Utilisateurs de Test

Pour tester l'authentification JWT :

| Email              | Pseudo        | Mot de passe | Rôle  |
| ------------------ | ------------- | ------------ | ----- |
| `admin@boxe.fr`    | AdminBoxe     | admin123     | ADMIN |
| `lucas@example.fr` | LucasBoxeur   | password123  | USER  |
| `sarah@example.fr` | SarahChampion | password456  | USER  |
| `test@boxe.fr`     | TestBoxer     | azerty       | USER  |

---

## 🔐 Obtenir un JWT Token

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

---

## 📋 Commandes Utiles

```bash
# Vider le cache
php bin/console cache:clear

# Vérifier le statut des migrations
php bin/console doctrine:migrations:status

# Afficher toutes les routes
php bin/console debug:router

# Arrêter le serveur
Ctrl + C

# Arrêter Docker
docker-compose down
```

---

## 🐛 Troubleshooting

### Erreur : "Cannot connect to MySQL"

```bash
# Vérifier que le conteneur est actif
docker-compose ps

# Redémarrer Docker
docker-compose restart
docker-compose logs -f database
```

### Erreur : "Missing column 'email'"

```bash
# Relancer les migrations
php bin/console doctrine:migrations:migrate --no-interaction
```

### Erreur : "JWT not valid"

```bash
# Vérifier les permissions des clés
chmod 600 config/jwt/private.pem
chmod 644 config/jwt/public.pem
```

---

## ✅ Checklist de Vérification

- [ ] `php --version` affiche PHP 8.2+
- [ ] `composer install` terminé sans erreur
- [ ] `config/jwt/private.pem` a les permissions `600`
- [ ] Docker MySQL est démarré (`docker-compose ps`)
- [ ] Base de données créée (`doctrine:database:create`)
- [ ] Migrations exécutées (`doctrine:migrations:migrate`)
- [ ] Serveur Symfony lancé (`php -S localhost:8000`)
- [ ] Login JWT fonctionne (`/api/login_check`)
- [ ] Documentation Swagger accessible (`/api/docs`)

---

**🚀 Une fois tout ça fait, tu es prêt à développer !**
