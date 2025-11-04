<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Contrôleur DocumentController
 * 
 * Gère les opérations CRUD pour les documents avec téléchargement de fichiers
 * et support multilingue pour les titres.
 */
class DocumentController extends Controller
{
    /**
     * Crée une nouvelle instance du contrôleur.
     * 
     * @param DocumentService $documentService Service gérant la logique métier des documents
     */
    public function __construct(protected DocumentService $documentService)
    {
    }

    /**
     * Affiche la liste paginée des documents.
     * 
     * @return View Vue de la liste des documents
     */
    public function index(): View
    {
        $this->authorize('viewAny', Document::class);

        $documents = $this->documentService->getPaginatedDocuments(10);

        return view('documents.index', compact('documents'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau document.
     * 
     * @return View Vue du formulaire de création
     */
    public function create(): View
    {
        $this->authorize('create', Document::class);

        return view('documents.create');
    }

    /**
     * Enregistre un nouveau document avec fichier dans la base de données.
     * 
     * @param StoreDocumentRequest $request Requête validée contenant les données et le fichier
     * @return RedirectResponse Redirection vers la liste des documents avec message de succès
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $this->authorize('create', Document::class);

        $this->documentService->createDocument(
            $request->validated(),
            $request->file('file'),
            $request->user()
        );

        return redirect()
            ->route('documents.index')
            ->with('success', __('documents.shared'));
    }

    /**
     * Télécharge le fichier d'un document spécifique.
     * 
     * @param Document $document Document à télécharger
     * @return BinaryFileResponse Réponse binaire contenant le fichier
     */
    public function show(Document $document): BinaryFileResponse
    {
        $this->authorize('download', $document);

        $filePath = $document->getFilePath();
        if (!file_exists($filePath)) {
            abort(404, __('documents.file_not_found'));
        }
        return response()->download($filePath, $document->original_filename);
    }

    /**
     * Affiche le formulaire de modification d'un document.
     * 
     * @param Document $document Document à modifier
     * @return View Vue du formulaire de modification
     */
    public function edit(Document $document): View
    {
        $this->authorize('update', $document);

        $translations = $document->translations->keyBy('locale');

        return view('documents.edit', compact('document', 'translations'));
    }

    /**
     * Met à jour un document existant et optionnellement son fichier.
     * 
     * @param UpdateDocumentRequest $request Requête validée contenant les nouvelles données
     * @param Document $document Document à mettre à jour
     * @return RedirectResponse Redirection vers la liste des documents avec message de succès
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $this->authorize('update', $document);

        $this->documentService->updateDocument(
            $document,
            $request->validated(),
            $request->file('file')
        );

        return redirect()
            ->route('documents.index')
            ->with('success', __('documents.updated'));
    }

    /**
     * Supprime un document et son fichier de la base de données et du stockage.
     * 
     * @param Document $document Document à supprimer
     * @return RedirectResponse Redirection vers la liste des documents avec message de succès
     */
    public function destroy(Document $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        $this->documentService->deleteDocument($document);

        return redirect()
            ->route('documents.index')
            ->with('success', __('documents.deleted'));
    }
}
