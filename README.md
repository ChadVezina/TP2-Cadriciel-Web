# TP2 — Cadriciel Web (Laravel)

Résumé consolidé et guide d'utilisation en français. Ce dépôt contient une application Laravel structurée autour de plusieurs ressources (Étudiants, Articles, Documents, Utilisateurs) et des améliorations visant la maintenabilité : architecture services, Form Requests, Policies, localisation, upload sécurisé, et tests.

Ce README fournit un aperçu global, les fonctionnalités principales, les prérequis, les étapes d'installation et d'exécution, ainsi que des informations sur l'architecture et la contribution.

---

## 📝 Aperçu

Application web Laravel (Blade + REST) pour gérer des entités métiers : étudiants, articles et documents. L'application inclut :

- CRUD complet pour les ressources principales (Étudiants, Articles, Documents)
- Authentification et gestion des utilisateurs
- Autorisation via Policies
- Uploads sécurisés (documents), gestion des types et validations
- Internationalisation (fr/en) avec affichage selon locale
- Architecture en couches (Controllers → Services → Models) pour la testabilité
- Seeders & factories pour données de test
- Tests unitaires et fonctionnels (PHPUnit / artisan test)

---

## ⚙️ Stack & prérequis

- PHP >= 8.2
- Composer
- Laravel 11/12 (selon configuration du projet)
- MySQL / MariaDB (ou autre DB compatible)
- Node.js (pour assets, Vite)
- npm (ou pnpm)

Vérifiez la version PHP et Composer avant d'installer.

---

## Structure importante

Extraits de la structure du projet (emplacements clés) :

- `app/Http/Controllers/` — Controllers RESTful
- `app/Services/` — Logique métier regroupée dans des services réutilisables
- `app/Models/` — Eloquent models (Etudiant, Ville, Article, Document, User, ...)
- `app/Http/Requests/` — Form Requests pour validation
- `app/Policies/` — Policies d'autorisation
- `resources/views/` — Vues Blade
- `database/migrations/`, `database/seeders/`, `database/factories/`
- `routes/web.php` — routes web, `routes/api.php` (si présent) — API

---

## Installation (locale)

1. Cloner le dépôt

```bash
git clone <repository-url>
cd TP2_Laravel
```

2. Installer dépendances PHP et JS

```bash
composer install
npm install
```

3. Copier et configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifier les variables `DB_*` dans le `.env` pour pointer vers votre base de données locale.

Exemple minimal `.env` pour MySQL :

```
APP_NAME=TP2
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=root
DB_PASSWORD=
```

4. Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed        # (optionnel) charger données de test
php artisan storage:link   # lier public/storage si nécessaire
```

Quelques seeders/factories sont fournis pour peupler `villes`, `etudiants`, etc.

5. Compiler les assets

```bash
npm run dev      # développement
npm run build    # production
```

6. Lancer le serveur

```bash
php artisan serve
# puis ouvrir http://127.0.0.1:8000
```

---

## Fonctionnalités principales

- Gestion des étudiants : création, lecture, mise à jour, suppression, recherche, pagination. Association avec une `ville`.
- Gestion des articles : CRUD, traductions (ArticleTranslation), affichage selon locale, permissions.
- Gestion des documents : upload sécurisé (PDF/ZIP/DOCX), téléchargement, validation des types et poids.
- Authentification : inscription, login, logout, gestion des sessions.
- Autorisation : Policies contrôlant qui peut modifier/supprimer une ressource.
- Internationalisation : support FR/EN via fichiers JSON et traduction des entités.
- Services : logique métier isolée dans `app/Services` pour testabilité.
- API Resources : `app/Http/Resources` pour formatage des réponses JSON (si routes API présentes).

---

## Architecture & bonnes pratiques

- Pattern controller → service : les controllers orchestrent, les services effectuent la logique métier.
- Form Requests pour validation et autorisation au niveau requête.
- Policies pour autorisation (ownership, rôles, restrictions).
- Models configurés avec `fillable`/`casts` et relations Eloquent.
- Utilisation des factories/seeders pour tests et données de développement.

Edge cases et validations courantes :

- Vérifier unicité d'email avec exception pour update
- Valider `ville_id` avec `exists:villes,id`
- Validation stricte des uploads (mimetype + taille)
- Traiter les utilisateurs orphelins lors de suppression (si applicable)

---

## Commandes utiles

```bash
# Migrations + seeders
php artisan migrate
php artisan db:seed

# Lancer le serveur
php artisan serve

# Tests
php artisan test

# Clear cache
php artisan cache:clear
php artisan config:clear

# Assets
npm run dev
npm run build
```

---

## Tests

Le projet contient des tests unitaires et fonctionnels (dans `tests/`). Exécuter :

```bash
php artisan test
```

Ajoutez des tests pour les Services et Controllers lorsque vous modifiez la logique métier.

---

## Routes principales (exemples)

Ressources exposées via routes RESTful (fichiers : `routes/web.php` et éventuellement `routes/api.php`) :

- `Route::resource('etudiants', EtudiantController::class);`
- `Route::resource('articles', ArticleController::class);`
- `Route::resource('documents', DocumentController::class);`

Consulter les controllers pour la liste complète des endpoints et middlewares associés (auth, throttle, etc.).

---