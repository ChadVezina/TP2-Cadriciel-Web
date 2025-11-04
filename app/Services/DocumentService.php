<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Get paginated list of documents.
     */
    public function getPaginatedDocuments(int $perPage = 10): LengthAwarePaginator
    {
        return Document::with(['user', 'translations'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create a new document with translations.
     */
    public function createDocument(array $data, UploadedFile $file, User $user): Document
    {
        $fileData = $this->storeFile($file);

        $document = Document::create([
            'user_id' => $user->id,
            'filename' => $fileData['filename'],
            'original_filename' => $fileData['original_filename'],
            'file_path' => $fileData['file_path'],
            'file_type' => $fileData['file_type'],
        ]);

        $this->syncTranslations($document, $data);

        return $document->fresh(['translations', 'user']);
    }

    /**
     * Update an existing document.
     */
    public function updateDocument(Document $document, array $data, ?UploadedFile $file = null): Document
    {
        if ($file) {
            $document->deleteFile();
            $fileData = $this->storeFile($file);
            $document->update([
                'filename' => $fileData['filename'],
                'original_filename' => $fileData['original_filename'],
                'file_path' => $fileData['file_path'],
                'file_type' => $fileData['file_type'],
            ]);
        }

        $this->syncTranslations($document, $data);

        return $document->fresh(['translations', 'user']);
    }

    /**
     * Delete a document and its file.
     */
    public function deleteDocument(Document $document): bool
    {
        $document->deleteFile();
        return $document->delete();
    }

    /**
     * Store uploaded file and return file data.
     */
    protected function storeFile(UploadedFile $file): array
    {
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs('documents', $filename, 'public');

        return [
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'file_type' => $extension,
        ];
    }

    /**
     * Sync document translations.
     */
    protected function syncTranslations(Document $document, array $data): void
    {
        $document->translations()->updateOrCreate(
            ['locale' => 'fr'],
            ['title' => $data['title_fr']]
        );

        $document->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['title' => $data['title_en']]
        );
    }

    /**
     * Get document with translations.
     */
    public function getDocumentWithTranslations(Document $document): Document
    {
        return $document->load('translations', 'user');
    }
}
