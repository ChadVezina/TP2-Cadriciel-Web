<?php

if (!function_exists('format_date')) {
    /**
     * Format a date using the application's default format.
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
     * Format a datetime using the application's default format.
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
     * Check if the current route matches the given route name(s).
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
     * Flash a message to the session.
     */
    function flash(string $message, string $type = 'success'): void
    {
        session()->flash('flash_message', $message);
        session()->flash('flash_type', $type);
    }
}

if (!function_exists('page_title')) {
    /**
     * Generate a page title with the application name.
     */
    function page_title(?string $title = null): string
    {
        $appName = config('app.name', 'Laravel');

        return $title ? "{$title} | {$appName}" : $appName;
    }
}

if (!function_exists('user_can')) {
    /**
     * Check if the authenticated user can perform an action on a model.
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
     * Check if the authenticated user cannot perform an action on a model.
     */
    function user_cannot(string $ability, $model = null): bool
    {
        return !user_can($ability, $model);
    }
}

if (!function_exists('file_size_format')) {
    /**
     * Format a file size in bytes to a human-readable format.
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
     * Generate a Gravatar URL for the given email.
     */
    function avatar_url(string $email, int $size = 200): string
    {
        $hash = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to a specified length.
     */
    function truncate_text(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . $suffix;
    }
}
