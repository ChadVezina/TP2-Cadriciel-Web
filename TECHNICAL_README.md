# TP2 Laravel - Documentation Technique

## 📋 Table des matières
- [Architecture](#architecture)
- [Conventions](#conventions)
- [Structure du projet](#structure-du-projet)
- [Installation](#installation)
- [Fonctionnalités](#fonctionnalités)
- [Tests](#tests)

## 🏗️ Architecture

Cette application suit les meilleures pratiques Laravel 12 et une architecture en couches :

### Couches architecturales

1. **Controllers** (`app/Http/Controllers/`)
   - Gèrent les requêtes HTTP
   - Délèguent la logique métier aux Services
   - Utilisent les Form Requests pour la validation
   - Appliquent les Policies pour l'autorisation

2. **Services** (`app/Http/Services/`)
   - Contiennent la logique métier
   - Orchestrent les opérations complexes
   - Isolent la logique des controllers

3. **Repositories** (Pattern à implémenter si nécessaire)
   - Abstraient l'accès aux données
   - Facilitent les tests et le changement de source de données

4. **Models** (`app/Models/`)
   - Représentent les entités métier
   - Définissent les relations Eloquent
   - Implémentent les scopes et accessors

5. **Policies** (`app/Policies/`)
   - Gèrent les autorisations
   - Centralisent la logique d'accès

6. **Form Requests** (`app/Http/Requests/`)
   - Valident les données entrantes
   - Centralisent les règles de validation
   - Gèrent l'autorisation au niveau requête

## 📐 Conventions

### Nommage

- **Controllers** : `{Resource}Controller` (ex: `ArticleController`)
- **Models** : Singulier, PascalCase (ex: `Article`, `Etudiant`)
- **Tables** : Pluriel, snake_case (ex: `articles`, `etudiants`)
- **Relations** : 
  - `hasMany/hasOne` : pluriel ou singulier selon le cas
  - `belongsTo` : singulier
- **Routes** : kebab-case (ex: `/etudiants`, `/articles/view-locale`)
- **Variables** : camelCase (ex: `$articleService`, `$viewLocale`)
- **Constantes** : SCREAMING_SNAKE_CASE (ex: `MAX_FILE_SIZE`)

### Organisation du code

```php
// Ordre des éléments dans un Controller
class ArticleController extends Controller
{
    // 1. Propriétés
    protected $articleService;
    
    // 2. Constructeur
    public function __construct(ArticleService $articleService) {}
    
    // 3. Méthodes de ressource (index, create, store, show, edit, update, destroy)
    public function index() {}
    
    // 4. Méthodes personnalisées
    public function changeViewLocale() {}
}

// Ordre des éléments dans un Model
class Article extends Model
{
    // 1. Traits
    use HasFactory;
    
    // 2. Constantes
    const STATUS_DRAFT = 'draft';
    
    // 3. Propriétés
    protected $fillable = [];
    
    // 4. Relations
    public function user(): BelongsTo {}
    
    // 5. Scopes
    public function scopePublished($query) {}
    
    // 6. Accessors/Mutators
    public function getTitleAttribute() {}
    
    // 7. Méthodes personnalisées
    public function isPublished(): bool {}
}
```

## 📂 Structure du projet

```
app/
├── Http/
│   ├── Controllers/          # Controllers RESTful
│   │   ├── ArticleController.php
│   │   ├── AuthController.php
│   │   ├── DocumentController.php
│   │   ├── EtudiantController.php
│   │   ├── LocaleController.php
│   │   └── UserController.php
│   ├── Middleware/           # Middlewares personnalisés
│   │   ├── EnsureUserOwnsResource.php
│   │   └── SetLocale.php
│   ├── Requests/            # Form Request classes
│   │   ├── LoginRequest.php
│   │   ├── StoreArticleRequest.php
│   │   ├── UpdateArticleRequest.php
│   │   ├── StoreDocumentRequest.php
│   │   ├── UpdateDocumentRequest.php
│   │   ├── StoreEtudiantRequest.php
│   │   ├── UpdateEtudiantRequest.php
│   │   ├── StoreUserRequest.php
│   │   └── UpdateUserRequest.php
│   └── Resources/           # API Resources
│       ├── ArticleResource.php
│       ├── DocumentResource.php
│       ├── EtudiantResource.php
│       └── UserResource.php
├── Models/                  # Eloquent Models
│   ├── Article.php
│   ├── ArticleTranslation.php
│   ├── Document.php
│   ├── DocumentTranslation.php
│   ├── Etudiant.php
│   ├── User.php
│   └── Ville.php
├── Policies/               # Authorization Policies
│   ├── ArticlePolicy.php
│   ├── DocumentPolicy.php
│   └── EtudiantPolicy.php
├── Services/               # Business Logic Services
│   ├── ArticleService.php
│   ├── AuthService.php
│   ├── DocumentService.php
│   ├── EtudiantService.php
│   └── UserService.php
├── View/
│   └── Components/         # Blade Components
│       ├── Alert.php
│       ├── Button.php
│       ├── Card.php
│       ├── FormInput.php
│       └── Modal.php
└── Helpers/
    └── helpers.php         # Fonctions helper globales

resources/
├── views/
│   ├── articles/           # Vues articles
│   ├── auth/              # Vues authentification
│   ├── documents/         # Vues documents
│   ├── etudiants/         # Vues étudiants
│   ├── users/             # Vues utilisateurs
│   └── components/        # Templates des components
│       ├── alert.blade.php
│       ├── button.blade.php
│       ├── card.blade.php
│       ├── form-input.blade.php
│       └── modal.blade.php
├── css/
│   ├── app.css            # Styles principaux
│   └── style.css          # Styles personnalisés
└── js/
    ├── app.js             # JavaScript principal
    └── bootstrap.js       # Configuration Bootstrap

config/
└── custom.php             # Configuration personnalisée

routes/
└── web.php                # Routes web organisées par groupes
```

## 🚀 Installation

```bash
# Cloner le repository
git clone <repository-url>
cd TP2_Laravel

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Copier et configurer .env
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tp2_laravel
DB_USERNAME=root
DB_PASSWORD=

# Exécuter les migrations
php artisan migrate

# Exécuter les seeders (optionnel)
php artisan db:seed

# Lier le stockage public
php artisan storage:link

# Compiler les assets
npm run build

# Lancer l'application
php artisan serve
```

## ✨ Fonctionnalités

### Gestion des Étudiants
- CRUD complet
- Recherche et filtrage
- Pagination
- Association avec une ville
- Création automatique d'un utilisateur lié

### Gestion des Articles
- CRUD avec authentification
- Système de traductions (FR/EN)
- Langues multiples par article
- Affichage dans la langue choisie
- Propriété utilisateur

### Gestion des Documents
- Upload de fichiers (PDF, ZIP, DOC, DOCX)
- Traductions des titres
- Téléchargement sécurisé
- Gestion des permissions propriétaire

### Authentification
- Login/Logout
- Inscription utilisateurs
- Sessions persistantes
- Protection CSRF

### Internationalisation
- Français / Anglais
- Changement de langue dynamique
- Traductions dans fichiers JSON
- Vue indépendante pour articles

## 🔧 Commandes utiles

```bash
# Lancer les tests
php artisan test

# Formater le code (Laravel Pint)
./vendor/bin/pint

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Générer des classes
php artisan make:controller NameController --resource
php artisan make:model Name -m
php artisan make:request StoreNameRequest
php artisan make:policy NamePolicy --model=Name
php artisan make:service NameService
php artisan make:resource NameResource

# Compiler les assets en mode développement
npm run dev

# Compiler les assets en mode production
npm run build
```

## 🧪 Tests

Les tests sont organisés en :
- **Unit Tests** : Tests unitaires des Services, Models
- **Feature Tests** : Tests d'intégration des Controllers, Routes

```bash
# Exécuter tous les tests
php artisan test

# Exécuter un test spécifique
php artisan test --filter=ArticleTest

# Exécuter avec couverture
php artisan test --coverage
```

## 📝 Conventions de commit

```
feat: Nouvelle fonctionnalité
fix: Correction de bug
refactor: Refactorisation du code
style: Changements de style (formatage)
docs: Documentation
test: Ajout/modification de tests
chore: Tâches de maintenance
```

## 🔒 Sécurité

- Validation des données via Form Requests
- Protection CSRF sur tous les formulaires
- Autorisation via Policies
- Hachage des mots de passe (bcrypt)
- Protection XSS (échappement automatique Blade)
- Validation des uploads de fichiers
- Sessions sécurisées

## 📚 Ressources

- [Documentation Laravel 12](https://laravel.com/docs/12.x)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP Standards (PSR)](https://www.php-fig.org/psr/)

---

**Version:** 1.0.0  
**Dernière mise à jour:** Novembre 2025
