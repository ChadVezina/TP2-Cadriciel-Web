<?php

namespace App\Enums;

/**
 * Énumération FileType
 * 
 * Définit les types de fichiers autorisés pour les téléchargements de documents.
 * Fournit des méthodes utilitaires pour obtenir les icônes, types MIME et extensions.
 */
enum FileType: string
{
    case PDF = 'pdf';
    case ZIP = 'zip';
    case DOC = 'doc';
    case DOCX = 'docx';

    /**
     * Récupère toutes les valeurs de types de fichiers possibles.
     *
     * @return array<string> Tableau des extensions de fichiers autorisées
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Récupère l'icône associée au type de fichier.
     * 
     * @return string Nom de l'icône à afficher
     */
    public function icon(): string
    {
        return match($this) {
            self::PDF => 'file-pdf',
            self::ZIP => 'file-archive',
            self::DOC, self::DOCX => 'file-word',
        };
    }

    /**
     * Récupère le type MIME associé au type de fichier.
     * 
     * @return string Type MIME du fichier
     */
    public function mimeType(): string
    {
        return match($this) {
            self::PDF => 'application/pdf',
            self::ZIP => 'application/zip',
            self::DOC => 'application/msword',
            self::DOCX => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        };
    }

    /**
     * Récupère les extensions autorisées sous forme de chaîne pour la validation.
     * 
     * @return string Extensions séparées par des virgules (ex: "pdf,zip,doc,docx")
     */
    public static function allowedExtensions(): string
    {
        return implode(',', self::values());
    }
}
