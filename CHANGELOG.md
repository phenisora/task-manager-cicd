# Changelog

Tous les changements notables de ce projet sont documentés dans ce fichier.


---

## [1.0.0] - 2026-01-15

### Ajouté
- Interface complète de gestion des tâches (CRUD)
- Filtrage des tâches par statut et priorité
- Pipeline CI/CD avec GitHub Actions (tests, PHPStan, CS Fixer, build frontend)
- Publication automatique de l'image Docker sur GHCR
- Documentation README et DEVOPS complète

---

## [0.4.0] - 2026-01-14

### Ajouté
- `feat(docker): add multi-stage Dockerfile for production`
- `feat(docker): add docker-compose with app, nginx, mysql, redis services`
- `feat(ci): add docker-publish workflow for GHCR`

---

## [0.3.0] - 2026-01-13

### Ajouté
- `feat(ci): add GitHub Actions CI pipeline with MySQL service`
- `feat(ci): add PHPStan static analysis job (level 5)`
- `feat(ci): add PHP CS Fixer code style check`
- `feat(ci): add frontend build job with artifact upload`

---

## [0.2.0] - 2024-01-12

### Ajouté
- `feat(tasks): add task listing with status and priority filters`
- `feat(tasks): add task detail view`
- `feat(tasks): add task creation and edit forms`
- `test(tasks): add TaskCreationTest with 6 test cases`
- `test(tasks): add TaskUpdateTest with 5 test cases`
- `test(tasks): add TaskListingTest with pagination`
- `test(tasks): add TaskDeletionTest`
- `test(tasks): add TaskModelTest unit tests`

---

## [0.1.0] - 2024-01-11

### Ajouté
- `feat(tasks): create task model with status and priority enums`
- `feat(tasks): add migration for tasks table`
- `feat(tasks): add TaskController with full CRUD`
- `feat(tasks): add TaskRequest form validation`
- `refactor(tasks): extract form validation to dedicated Request class`
- `docs: add README with setup instructions`
- `chore: initial Laravel project setup`
