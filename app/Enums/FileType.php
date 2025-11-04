<?php

namespace App\Enums;

enum FileType: string
{
    case PDF = 'pdf';
    case ZIP = 'zip';
    case DOC = 'doc';
    case DOCX = 'docx';

    /**
     * Get all file type values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get file type icon.
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
     * Get file type MIME type.
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
     * Get allowed extensions as string for validation.
     */
    public static function allowedExtensions(): string
    {
        return implode(',', self::values());
    }
}
