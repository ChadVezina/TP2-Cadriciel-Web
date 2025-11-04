<?php

namespace App\Enums;

enum Language: string
{
    case FRENCH = 'fr';
    case ENGLISH = 'en';

    /**
     * Get all language values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get language label.
     */
    public function label(): string
    {
        return match($this) {
            self::FRENCH => 'Français',
            self::ENGLISH => 'English',
        };
    }

    /**
     * Get language from string value.
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
