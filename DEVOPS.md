# 📘 DEVOPS.md — Documentation Technique

## 1. Architecture de la Pipeline CI/CD

```
┌────────────────────────────────────────────────────────────────┐
│                        DÉVELOPPEUR                             │
│  git commit → git push → Pull Request → Code Review → Merge   │
└───────────────────────────┬────────────────────────────────────┘
                            │ Déclenche automatiquement
                            ▼
┌────────────────────────────────────────────────────────────────┐
│                     GITHUB ACTIONS                             │
│                                                                │
│  ┌─────────────┐  ┌─────────────┐  ┌────────┐  ┌──────────┐  │
│  │    TESTS    │  │   PHPSTAN   │  │  CSS   │  │FRONTEND  │  │
│  │  PHPUnit    │  │  Niveau 5   │  │ FIXER  │  │  BUILD   │  │
│  │  MySQL      │  │  Analyse    │  │ Check  │  │   Vite   │  │
│  │  Coverage   │  │  statique   │  │        │  │          │  │
│  │   ≥ 70%     │  │             │  │        │  │          │  │
│  └──────┬──────┘  └──────┬──────┘  └───┬────┘  └────┬─────┘  │
│         └────────────────┴─────────────┴────────────┘         │
│                          │ Tous les jobs passent               │
└──────────────────────────┬─────────────────────────────────────┘
                           │
                           ▼
┌────────────────────────────────────────────────────────────────┐
│                    DOCKER PUBLISH                              │
│                                                                │
│   Build image → Push sur GHCR                                 │
│   Tags : latest | sha-abc1234 | v1.0.0                        │
│   Registry : ghcr.io/username/task-manager-cicd               │
└────────────────────────────────────────────────────────────────┘
```

---

## 2. Stratégie de branches Git — GitHub Flow

Nous avons choisi **GitHub Flow** (workflow simple) plutôt que Git Flow car :
- L'équipe est débutante → moins de complexité
- Déploiement continu depuis `main` → une seule branche stable
- Adapté aux projets avec des livraisons fréquentes

### Règles appliquées

```
main           → branche protégée, toujours stable
feat/xxx       → nouvelle fonctionnalité
fix/xxx        → correction de bug
test/xxx       → ajout de tests
docs/xxx       → documentation
refactor/xxx   → refactorisation
```

### Convention de commits (Conventional Commits)

```
feat(tasks): add task creation form
fix(tasks): correct status filter bug
test(tasks): add unit tests for TaskModel
docs: update README with Docker instructions
refactor(controller): simplify task update logic
chore: initial Laravel project setup
```

---

## 3. Processus de déploiement

### Déploiement local avec Docker

```bash
# Démarrage complet en une commande
docker compose up -d --build

# Vérifier que tous les services tournent
docker compose ps

# Logs en temps réel
docker compose logs -f app
```

### Flux de déploiement automatique

```
1. Developer → git push origin feat/ma-feature
2. GitHub    → Ouvre une Pull Request vers main
3. CI/CD     → Lance les 4 jobs automatiquement
4. Team      → Code Review (approbation requise)
5. Merge     → Pipeline se relance sur main
6. Docker    → Image buildée et publiée sur GHCR
```

---

## 4. Configuration Docker — Justification des choix

### Pourquoi multi-stage build ?

Le Dockerfile utilise **2 stages** :

| Stage | Image | Rôle |
|-------|-------|------|
| `composer-build` | `composer:2.6` | Installe les dépendances PHP |
| Production | `php:8.2-fpm-alpine` | Image finale légère |

**Avantages :**
- L'image finale ne contient pas Composer (outil de dev)
- Image plus petite → déploiement plus rapide
- Séparation claire build / runtime

### Pourquoi Alpine ?

`php:8.2-fpm-alpine` plutôt que `php:8.2-fpm-debian` :
- Alpine Linux pèse ~5 MB vs ~100 MB pour Debian
- Surface d'attaque réduite (sécurité)
- Démarrage plus rapide

### Pourquoi un utilisateur non-root ?

Par principe de **least privilege** : le processus PHP-FPM tourne
avec l'utilisateur `appuser` (uid 1001) et non `root`.
Si un attaquant exploite une faille PHP, il n'aura pas les droits root sur le système.

### Services Docker et leur rôle

| Service | Image | Rôle |
|---------|-------|------|
| `app` | Notre Dockerfile | PHP-FPM — exécute Laravel |
| `nginx` | `nginx:1.25-alpine` | Serveur web — reçoit les requêtes HTTP |
| `mysql` | `mysql:8.0` | Base de données relationnelle |
| `redis` | `redis:7-alpine` | Cache sessions et données |

### Réseau interne `laravel`

Tous les services communiquent via un réseau Docker privé.
Seul Nginx est exposé sur le port 8080 de la machine hôte.

---

## 5. Outils utilisés et leur rôle

### PHPStan — Analyse statique
- **Rôle :** Détecte les bugs potentiels sans exécuter le code
- **Niveau 5 :** Détecte les types mal utilisés, variables indéfinies, etc.
- **Fichier de config :** `phpstan.neon`
- **Commande :** `vendor/bin/phpstan analyse`

```
Exemple de bug détecté par PHPStan :
  function getTask(int $id): Task {
      return Task::find($id); // ← PHPStan détecte que find() peut retourner null !
  }
```

### PHP CS Fixer — Style de code
- **Rôle :** Impose un style de code uniforme dans toute l'équipe
- **Standard :** PSR-12 (standard PHP)
- **Fichier de config :** `.php-cs-fixer.php`
- **Mode CI :** `--dry-run` (ne corrige pas, signale seulement)

### GitHub Actions — Automatisation CI/CD
- **Rôle :** Exécute les tests et vérifications à chaque push
- **Fichiers :** `.github/workflows/ci.yml` et `docker-publish.yml`
- **Avantage :** Gratuit pour les repos publics

### GHCR (GitHub Container Registry)
- **Rôle :** Stocke les images Docker du projet
- **Avantage :** Intégré à GitHub, pas besoin de compte Docker Hub
- **Tags utilisés :** `latest`, `sha-xxxxxxx`, `v1.0.0`

---

## 6. Difficultés rencontrées et solutions

### Difficulté 1 : Configuration MySQL dans la CI
**Problème :** Le service MySQL prend du temps à démarrer, les tests échouaient.  
**Solution :** Ajout d'un `healthcheck` dans le workflow pour attendre que MySQL soit prêt avant de lancer les migrations.

```yaml
options: >-
  --health-cmd="mysqladmin ping"
  --health-interval=10s
  --health-retries=3
```

### Difficulté 2 : Droits sur les dossiers storage/ dans Docker
**Problème :** Laravel ne pouvait pas écrire dans `storage/` car l'utilisateur non-root n'avait pas les droits.  
**Solution :** Ajout d'un `chown` dans le Dockerfile après la copie du code.

```dockerfile
RUN chown -R appuser:appgroup /var/www/html/storage
```

### Difficulté 3 : Cache Composer entre les jobs CI
**Problème :** Chaque job réinstallait toutes les dépendances → pipeline lente.  
**Solution :** Utilisation de `actions/cache@v3` avec une clé basée sur le hash du `composer.lock`.

```yaml
- uses: actions/cache@v3
  with:
    path: vendor
    key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
```

### Difficulté 4 : Coordination en équipe sur Git
**Problème :** Conflits fréquents car plusieurs développeurs travaillaient sur les mêmes fichiers.  
**Solution :** Division claire du travail 