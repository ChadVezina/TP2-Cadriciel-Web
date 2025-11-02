# Maisonneuve_e2496523 — Gestion des étudiants (Laravel + Blade, REST)

Application Laravel pour collecter, afficher, créer, mettre à jour et supprimer des **étudiants** du Collège Maisonneuve, liée à une table **villes**. UI en Blade (Bootstrap), contrôleur RESTful, données initiales via seeders/factories. Pensée pour évoluer vers un réseau social étudiant.

---

## 🚀 Stack & prérequis

- PHP ≥ 8.2, Composer ≥ 2.x  
- Laravel 11.x  
- MySQL
- Node.js ≥ 18  
- Navigateur moderne

---

## 📁 Structure (extrait)

```
app/
  Http/Controllers/EtudiantController.php
  Models/Etudiant.php
  Models/Ville.php
database/
  factories/EtudiantFactory.php
  migrations/*_create_villes_table.php
  migrations/*_create_etudiants_table.php
  seeders/DatabaseSeeder.php
  seeders/EtudiantSeeder.php
  seeders/VilleSeeder.php
public/
  css/style.css (optionnel)
resources/
  views/layout.blade.php
  views/etudiants/index.blade.php
  views/etudiants/create.blade.php
  views/etudiants/edit.blade.php
  views/etudiants/show.blade.php
routes/
  web.php
```

---

## ⚙️ Installation & configuration

```bash
# 1) Cloner
git clone <votre-repo.git>
cd Maisonneuvee2496523

# 2) Dépendances
composer install

# 3) Variables d'env
cp .env.example .env
```

**.env (exemple MySQL)**
```
APP_NAME=name
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

---

## 🗃️ Base de données

```bash
# Migrations
php artisan migrate

# Seed (15 villes + 100 étudiants)
php artisan db:seed
# ou
php artisan db:seed --class=VilleSeeder
php artisan db:seed --class=EtudiantSeeder
```

> Tables créées : **villes** (id, nom), **etudiants** (id, nom, adresse, telephone, email unique, date_naissance, ville_id FK).

---

## 🧱 Artisan (rappel des commandes clés)

```bash
# Projet (déjà fait côté auteur)
laravel new Maisonneuve_e2496523

# Modèles + migrations
php artisan make:model Ville -m
php artisan make:model Etudiant -m

# Factory + seeders
php artisan make:factory EtudiantFactory --model=Etudiant
php artisan make:seeder VilleSeeder
php artisan make:seeder EtudiantSeeder

# Contrôleur REST
php artisan make:controller EtudiantController --resource
php artisan make:controller AuthController -r
php artisan make:controller UserController -m User
```

---

## 🧭 Routes & endpoints

`routes/web.php`
```php
use App\Http\Controllers\EtudiantController;
Route::resource('etudiants', EtudiantController::class);
```

| Verbe | URI                          | Action   | Contrôleur                     |
|------:|------------------------------|----------|--------------------------------|
| GET   | /etudiants                   | index    | EtudiantController@index       |
| GET   | /etudiants/create            | create   | EtudiantController@create      |
| POST  | /etudiants                   | store    | EtudiantController@store       |
| GET   | /etudiants/{etudiant}        | show     | EtudiantController@show        |
| GET   | /etudiants/{etudiant}/edit   | edit     | EtudiantController@edit        |
| PUT   | /etudiants/{etudiant}        | update   | EtudiantController@update      |
| DELETE| /etudiants/{etudiant}        | destroy  | EtudiantController@destroy     |

---

## 🖥️ Lancer l’app

```bash
php artisan serve
# http://127.0.0.1:8000/etudiants
```

---

## 🧩 Vues principales (Blade)

- `resources/views/layout.blade.php` — layout global (Bootstrap + nav)
- `resources/views/etudiants/index.blade.php` — liste + actions Voir/Modifier/Supprimer
- `resources/views/etudiants/create.blade.php` — formulaire de création (select des villes)
- `resources/views/etudiants/edit.blade.php` — formulaire d’édition
- `resources/views/etudiants/show.blade.php` — détails d’un étudiant

> Les formulaires utilisent `@csrf`, validations côté serveur et retours d’erreurs (`$errors`).

---

## ✅ Validation (store/update)

- `nom` : required|string|max:255  
- `adresse` : required|string|max:255  
- `telephone` : required|string|max:50  
- `email` : required|email|unique:etudiants,email *(update : unique sauf l’ID courant)*  
- `date_naissance` : required|date  
- `ville_id` : required|exists:villes,id

---

## 🔗 Relations Eloquent

- `Etudiant` **belongsTo** `Ville` (`ville_id`)  
- `Ville` **hasMany** `Etudiant`

---

## 🔒 Sécurité & bonnes pratiques

- CSRF par défaut (forms Blade)  
- Validation systématique des payloads  
- Colonnes `fillable` définies sur les modèles (mass assignment safe)  
- Email unique sur `etudiants.email`

---

