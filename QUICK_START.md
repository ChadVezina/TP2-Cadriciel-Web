# Guide de démarrage rapide - Application refactorisée

## 🚀 Démarrage

### 1. Vérifier l'environnement

```bash
php -v  # Doit être >= 8.2
composer -v
npm -v
```

### 2. Installer les dépendances

```bash
# Dans le dossier du projet
cd /c/Users/Admin/Desktop/DEV/TP2_Laravel

# Dépendances PHP
composer install
composer dump-autoload

# Dépendances JavaScript
npm install
```

### 3. Configuration

```bash
# Copier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Configurer la base de données dans .env
# Par défaut: SQLite
# Pour MySQL, décommenter et configurer les lignes DB_*
```

### 4. Base de données

```bash
# Créer la base de données (si MySQL)
# mysql -u root -p
# CREATE DATABASE tp2_laravel;

# Lancer les migrations
php artisan migrate

# Optionnel: Peupler avec des données de test
php artisan db:seed
```

### 5. Lier le stockage

```bash
php artisan storage:link
```

### 6. Compiler les assets

```bash
# Développement
npm run dev

# Production
npm run build
```

### 7. Lancer l'application

```bash
php artisan serve
```

Accédez à: http://localhost:8000

---

## 📖 Utilisation des nouvelles fonctionnalités

### Utiliser les Blade Components

#### Alert
```blade
{{-- Dans une vue --}}
<x-alert type="success">Opération réussie!</x-alert>
<x-alert type="danger" :message="$errors->first()" />
```

#### Button
```blade
<x-button variant="primary" icon="plus" href="{{ route('articles.create') }}">
    Nouvel article
</x-button>

<x-button type="submit" variant="success">Enregistrer</x-button>
```

#### Form Input
```blade
<x-form-input 
    name="email" 
    type="email" 
    label="Adresse email"
    placeholder="exemple@email.com"
    required 
/>
```

#### Card
```blade
<x-card title="Mes informations">
    <p>Contenu de la carte</p>
</x-card>
```

### Utiliser les Helpers

```php
// Dans les vues ou controllers
{{ format_date($student->birthdate) }}
{{ format_datetime($article->created_at) }}
{{ file_size_format($document->file_size) }}
{{ truncate_text($article->content, 100) }}

// Classes CSS actives
<li class="{{ active_route('articles.*') }}">Articles</li>

// Permissions
@if(user_can('update', $article))
    <a href="{{ route('articles.edit', $article) }}">Modifier</a>
@endif
```

### Utiliser les Scopes dans les queries

```php
// Dans un controller ou service
$students = Etudiant::query()
    ->search($request->search)
    ->fromCity($cityId)
    ->paginate(15);

$articles = Article::query()
    ->byUser(auth()->id())
    ->inLanguage('fr')
    ->latest()
    ->get();

$users = User::query()
    ->students()
    ->search($search)
    ->paginate(20);
```

### Créer un nouveau module (exemple: Categories)

#### 1. Créer la migration
```bash
php artisan make:migration create_categories_table
```

#### 2. Créer le Model avec relations
```bash
php artisan make:model Category -m
```

#### 3. Créer le Controller
```bash
php artisan make:controller CategoryController --resource
```

#### 4. Créer les Form Requests
```bash
php artisan make:request StoreCategoryRequest
php artisan make:request UpdateCategoryRequest
```

#### 5. Créer le Service
```php
// Créer manuellement app/Services/CategoryService.php
```

#### 6. Créer la Policy
```bash
php artisan make:policy CategoryPolicy --model=Category
```

#### 7. Enregistrer la Policy dans AppServiceProvider
```php
protected $policies = [
    // ...
    Category::class => CategoryPolicy::class,
];
```

#### 8. Ajouter les routes
```php
// routes/web.php
Route::resource('categories', CategoryController::class);
```

---

## 🧪 Lancer les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=AuthenticationTest

# Avec couverture
php artisan test --coverage
```

---

## 🔧 Commandes utiles

### Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Générer du code
```bash
# Controller
php artisan make:controller NomController --resource

# Model avec migration
php artisan make:model Nom -m

# Form Request
php artisan make:request StoreNomRequest

# Policy
php artisan make:policy NomPolicy --model=Nom

# Resource (API)
php artisan make:resource NomResource

# Middleware
php artisan make:middleware NomMiddleware

# Seeder
php artisan make:seeder NomSeeder
```

### Base de données
```bash
# Nouvelle migration
php artisan make:migration nom_de_la_migration

# Lancer les migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Reset et relancer
php artisan migrate:fresh

# Avec seeders
php artisan migrate:fresh --seed
```

### Maintenance
```bash
# Mode maintenance ON
php artisan down

# Mode maintenance OFF
php artisan up

# Liste des routes
php artisan route:list

# Optimiser pour production
php artisan optimize
composer install --optimize-autoloader --no-dev
```

---

## 📝 Conventions à suivre

### Nommage
- **Controllers**: `{Resource}Controller` - PascalCase
- **Models**: Singulier, PascalCase
- **Tables**: Pluriel, snake_case
- **Méthodes**: camelCase
- **Variables**: camelCase
- **Constantes**: SCREAMING_SNAKE_CASE

### Structure d'un Controller
```php
class ArticleController extends Controller
{
    // 1. Constructor avec injection de dépendances
    public function __construct(protected ArticleService $service) {}
    
    // 2. Méthodes RESTful (index, create, store, show, edit, update, destroy)
    // 3. Méthodes personnalisées
}
```

### Structure d'un Model
```php
class Article extends Model
{
    // 1. Traits
    use HasFactory;
    
    // 2. Constantes
    // 3. Propriétés ($fillable, $casts, etc.)
    // 4. Relations
    // 5. Scopes
    // 6. Accessors/Mutators
    // 7. Méthodes helper
}
```

---

## 🐛 Dépannage

### Problème: "Class not found"
```bash
composer dump-autoload
php artisan clear-compiled
```

### Problème: Erreurs de permission (storage, bootstrap/cache)
```bash
chmod -R 775 storage bootstrap/cache
```

### Problème: Vues non mises à jour
```bash
php artisan view:clear
```

### Problème: Configuration en cache
```bash
php artisan config:clear
```

### Problème: Routes non trouvées
```bash
php artisan route:clear
php artisan route:cache
```

---

## 📚 Documentation

- **README principal**: `README.md`
- **Documentation technique**: `TECHNICAL_README.md`
- **Résumé refactoring**: `REFACTORING_SUMMARY.md`
- **Guide rapide**: `QUICK_START.md` (ce fichier)

---

## 🆘 Support

### Ressources Laravel
- [Documentation Laravel 12](https://laravel.com/docs/12.x)
- [Laracasts](https://laracasts.com)
- [Laravel News](https://laravel-news.com)

### Communauté
- [Laravel Forums](https://laracasts.com/discuss)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [Laravel Discord](https://discord.gg/laravel)

---

**Version**: 1.0.0  
**Date**: Novembre 2025  
**Status**: ✅ Production Ready
