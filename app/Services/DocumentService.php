<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Service DocumentService
 * 
 * Gère la logique métier liée aux documents.
 * Fournit des méthodes pour créer, mettre à jour, supprimer et récupérer
 * des documents avec leur gestion de fichiers et traductions multilingues.
 */
class DocumentService
{
    /**
     * Récupère une liste paginée de documents avec leurs traductions.
     * 
     * @param int $perPage Nombre de documents par page (défaut: 10)
     * @return LengthAwarePaginator Liste paginée de documents avec utilisateur et traductions
     */
    public function getPaginatedDocuments(int $perPage = 10): LengthAwarePaginator
    {
        return Document::with(['user', 'translations'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Crée un nouveau document avec stockage du fichier et traductions.
     * 
     * Le fichier est stocké avec un nom unique UUID et les traductions
     * du titre sont créées dans les deux langues.
     * 
     * @param array $data Données du document incluant les traductions
     * @param UploadedFile $file Fichier téléchargé à stocker
     * @param User $user Utilisateur propriétaire du document
     * @return Document Document créé avec traductions et utilisateur chargés
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
     * Met à jour un document existant et optionnellement son fichier.
     * 
     * Si un nouveau fichier est fourni, l'ancien est supprimé et remplacé.
     * Les traductions sont toujours synchronisées.
     * 
     * @param Document $document Document à mettre à jour
     * @param array $data Nouvelles données du document incluant les traductions
     * @param UploadedFile|null $file Nouveau fichier optionnel à stocker
     * @return Document Document mis à jour avec traductions et utilisateur chargés
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
     * Supprime un document et son fichier physique du stockage.
     * 
     * @param Document $document Document à supprimer
     * @return bool True si la suppression a réussi
     */
    public function deleteDocument(Document $document): bool
    {
        $document->deleteFile();
        return $document->delete();
    }

    /**
     * Stocke un fichier téléchargé et retourne ses métadonnées.
     * 
     * Le fichier est stocké dans le dossier 'documents' avec un nom UUID unique
     * pour éviter les conflits de noms.
     * 
     * @param UploadedFile $file Fichier à stocker
     * @return array Tableau contenant filename, original_filename, file_path et file_type
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
     * Synchronise les traductions d'un document dans les deux langues.
     * 
     * Crée ou met à jour les traductions du titre en français et en anglais.
     * 
     * @param Document $document Document dont les traductions doivent être synchronisées
     * @param array $data Données contenant les traductions (title_fr, title_en)
     * @return void
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
     * Récupère un document avec ses traductions et son utilisateur.
     * 
     * @param Document $document Document à charger
     * @return Document Document avec relations chargées
     */
    public function getDocumentWithTranslations(Document $document): Document
    {
        return $document->load('translations', 'user');
    }
}
