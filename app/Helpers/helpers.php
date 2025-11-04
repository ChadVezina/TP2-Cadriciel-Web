<?php

/**
 * Fichier de fonctions utilitaires globales
 * 
 * Ce fichier contient des fonctions helpers utilisées à travers l'application
 * pour simplifier les tâches courantes comme le formatage de dates, la gestion
 * des autorisations, et l'affichage de messages.
 */

if (!function_exists('format_date')) {
    /**
     * Formate une date selon le format par défaut de l'application.
     * 
     * @param mixed $date Date à formater (chaîne, Carbon, ou DateTime)
     * @param string|null $format Format personnalisé optionnel (défaut: d/m/Y)
     * @return string Date formatée
     */
    function format_date($date, ?string $format = null): string
    {
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }

        $format = $format ?? config('custom.date_formats.display', 'd/m/Y');

        return $date->format($format);
    }
}

if (!function_exists('format_datetime')) {
    /**
     * Formate une date et heure selon le format par défaut de l'application.
     * 
     * @param mixed $datetime Date et heure à formater (chaîne, Carbon, ou DateTime)
     * @param string|null $format Format personnalisé optionnel (défaut: d/m/Y H:i)
     * @return string Date et heure formatées
     */
    function format_datetime($datetime, ?string $format = null): string
    {
        if (!$datetime instanceof \Carbon\Carbon) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        $format = $format ?? config('custom.date_formats.datetime', 'd/m/Y H:i');

        return $datetime->format($format);
    }
}

if (!function_exists('active_route')) {
    /**
     * Vérifie si la route actuelle correspond au(x) nom(s) de route donné(s).
     * 
     * Utile pour ajouter une classe CSS aux liens de navigation actifs.
     * 
     * @param string|array $routes Nom(s) de route à vérifier
     * @param string $class Classe CSS à retourner si la route correspond (défaut: 'active')
     * @return string Classe CSS si la route correspond, chaîne vide sinon
     */
    function active_route($routes, string $class = 'active'): string
    {
        $routes = is_array($routes) ? $routes : [$routes];

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $class;
            }
        }

        return '';
    }
}

if (!function_exists('flash')) {
    /**
     * Enregistre un message flash dans la session.
     * 
     * @param string $message Message à afficher
     * @param string $type Type de message (success, error, warning, info)
     * @return void
     */
    function flash(string $message, string $type = 'success'): void
    {
        session()->flash('flash_message', $message);
        session()->flash('flash_type', $type);
    }
}

if (!function_exists('page_title')) {
    /**
     * Génère un titre de page avec le nom de l'application.
     * 
     * @param string|null $title Titre de la page (optionnel)
     * @return string Titre complet de la page
     */
    function page_title(?string $title = null): string
    {
        $appName = config('app.name', 'Laravel');

        return $title ? "{$title} | {$appName}" : $appName;
    }
}

if (!function_exists('user_can')) {
    /**
     * Vérifie si l'utilisateur authentifié peut effectuer une action sur un modèle.
     * 
     * @param string $ability Nom de l'autorisation à vérifier
     * @param mixed $model Modèle sur lequel vérifier l'autorisation (optionnel)
     * @return bool True si l'utilisateur a l'autorisation
     */
    function user_can(string $ability, $model = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->can($ability, $model);
    }
}

if (!function_exists('user_cannot')) {
    /**
     * Vérifie si l'utilisateur authentifié ne peut pas effectuer une action sur un modèle.
     * 
     * @param string $ability Nom de l'autorisation à vérifier
     * @param mixed $model Modèle sur lequel vérifier l'autorisation (optionnel)
     * @return bool True si l'utilisateur n'a pas l'autorisation
     */
    function user_cannot(string $ability, $model = null): bool
    {
        return !user_can($ability, $model);
    }
}

if (!function_exists('file_size_format')) {
    /**
     * Formate une taille de fichier en octets vers un format lisible.
     * 
     * Convertit les octets en unités appropriées (B, KB, MB, GB, TB).
     * 
     * @param int $bytes Taille en octets
     * @param int $decimals Nombre de décimales (défaut: 2)
     * @return string Taille formatée avec unité
     */
    function file_size_format(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = floor(($bytes > 0 ? log($bytes) : 0) / log(1024));
        $power = min($power, count($units) - 1);

        return round($bytes / pow(1024, $power), $decimals) . ' ' . $units[$power];
    }
}

if (!function_exists('avatar_url')) {
    /**
     * Génère une URL Gravatar pour l'adresse courriel donnée.
     * 
     * Utilise le service Gravatar pour récupérer l'avatar associé à un courriel.
     * 
     * @param string $email Adresse courriel
     * @param int $size Taille de l'avatar en pixels (défaut: 200)
     * @return string URL de l'avatar Gravatar
     */
    function avatar_url(string $email, int $size = 200): string
    {
        $hash = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Tronque un texte à une longueur spécifiée.
     * 
     * Ajoute un suffixe (par défaut '...') si le texte est tronqué.
     * 
     * @param string $text Texte à tronquer
     * @param int $length Longueur maximale (défaut: 100)
     * @param string $suffix Suffixe à ajouter si tronqué (défaut: '...')
     * @return string Texte tronqué
     */
    function truncate_text(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . $suffix;
    }
}
