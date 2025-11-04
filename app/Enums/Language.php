<?php

namespace App\Enums;

/**
 * Énumération Language
 * 
 * Définit les langues supportées par l'application pour l'interface
 * et les traductions de contenu.
 */
enum Language: string
{
    case FRENCH = 'fr';
    case ENGLISH = 'en';

    /**
     * Récupère toutes les valeurs de langues possibles.
     *
     * @return array<string> Tableau des codes de langues (fr, en)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Récupère le libellé affiché de la langue.
     * 
     * @return string Nom de la langue dans sa propre langue
     */
    public function label(): string
    {
        return match($this) {
            self::FRENCH => 'Français',
            self::ENGLISH => 'English',
        };
    }

    /**
     * Crée une instance de Language à partir d'une chaîne de caractères.
     * 
     * @param string $value Code de langue (fr ou en)
     * @return self|null Instance de Language ou null si valeur invalide
     */
    public static function fromString(string $value): ?self
    {
        return match($value) {
            'fr' => self::FRENCH,
            'en' => self::ENGLISH,
            default => null,
        };
    }
}
