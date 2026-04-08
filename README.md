
---

## 📋 Description du projet

**Task Manager** est une application web de gestion de tâches développée avec **Laravel **.
Ce projet a été réalisé dans le cadre d'une formation  academique  pour mettre en place une infrastructure CI/CD complète de A à Z.

### Fonctionnalités
- ➕ Créer une tâche (titre, description, statut, priorité, date limite)
- ✏️ Modifier une tâche existante
- 🗑️ Supprimer une tâche
- 📋 Lister toutes les tâches avec filtres (statut, priorité)
- 👁️ Voir le détail d'une tâche

---

## 🛠️ Prérequis

| Outil | Version minimale |
|-------|-----------------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 20.x |
| Docker | 24.x |
| Docker Compose | v2 |
| Git | 2.x |

---

## 🚀 Installation locale (sans Docker)

```bash
# 1. Cloner le projet
git clone https://github.com/phenisora/task-manager-cicd.git

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JavaScript
npm install

# 4. Copier la configuration
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Configurer votre .env (base de données, etc.)
# Modifier DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 7. Lancer les migrations
php artisan migrate

# 8. Compiler les assets
npm run build

# 9. Démarrer le serveur
php artisan serve
```

L'application est accessible sur : **http://localhost:8000**

---

## 🐳 Installation avec Docker (recommandé)

```bash
# 1. Cloner le projet
git clone https://github.com/phenisora/task-manager-cicd.git

# 2. Copier la configuration
cp .env.example .env

# 3. Démarrer tous les services
docker compose up -d --build

# 4. Générer la clé d'application
docker compose exec app php artisan key:generate

# 5. Lancer les migrations
docker compose exec app php artisan migrate

### Services Docker disponibles
| Service | URL/Port |
|---------|----------|
| Application | http://localhost:8080 |
| MySQL | localhost:3306 |
| Redis | localhost:6379 |

---

## 🧪 Commandes utiles

```bash
# Lancer tous les tests
php artisan test

# Lancer les tests avec couverture de code (min 70%)
php artisan test --coverage --min=70

# Analyse statique PHPStan
vendor/bin/phpstan analyse

# Vérification du style de code
vendor/bin/php-cs-fixer fix --dry-run --diff

# Corriger automatiquement le style
vendor/bin/php-cs-fixer fix

# Avec Docker
docker compose exec app php artisan test
docker compose exec app vendor/bin/phpstan analyse
```

---

## 🔄 Pipeline CI/CD

La pipeline se déclenche automatiquement sur chaque **push** et **Pull Request** vers `main`.

```
Push/PR vers main
       │
       ▼
┌─────────────────────────────────────────┐
│              GitHub Actions             │
├──────────┬──────────┬───────┬──────────┤
│  Tests   │ PHPStan  │  CS   │ Frontend │
│  + MySQL │  Niveau5 │ Fixer │  Build   │
│  + 70%   │          │       │          │
│ coverage │          │       │          │
└──────────┴──────────┴───────┴──────────┘
       │ (si tous les jobs passent)
       ▼
┌─────────────────────────────────────────┐
│         Docker Build & Push             │
│         → ghcr.io (GHCR)               │
│         Tags: latest, sha, version      │
└─────────────────────────────────────────┘
```

### Jobs de la pipeline

| Job | Description |
|-----|-------------|
| `tests` | Exécute les tests PHPUnit avec MySQL + coverage 70% |
| `phpstan` | Analyse statique du code (niveau 5) |
| `code-style` | Vérifie le style avec PHP CS Fixer |
| `frontend` | Build des assets Vite + upload artifact |

---

## 🌿 Workflow Git (GitHub Flow)

```
main ──────────────────────────────────────────→
  │                           ↑
  └──→ feat/task-model ───────┘ (Pull Request)
  └──→ feat/task-crud  ───────┘ (Pull Request)
  └──→ test/unit-tests ───────┘ (Pull Request)
```



---

## 👥 Équipe

Projet réalisé par le groupe dans le cadre de la formation **Laravel/PHP - DevOps**  
Enseignant : **Serigne Diagne**
