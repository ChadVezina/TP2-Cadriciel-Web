<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Document;
use App\Models\DocumentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des documents avec pagination
     */
    public function index()
    {
        $documents = Document::with(['user', 'translations'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Enregistrer un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,zip,doc,docx|max:10240', // Max 10MB
        ], [
            'title_fr.required' => 'Le titre en français est obligatoire.',
            'title_en.required' => 'Le titre en anglais est obligatoire.',
            'file.required' => 'Le fichier est obligatoire.',
            'file.mimes' => 'Le fichier doit être au format PDF, ZIP, DOC ou DOCX.',
            'file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        // Télécharger le fichier
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $filePath = $file->storeAs('documents', $filename, 'public');

        // Créer le document
        $document = Document::create([
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'file_path' => $filePath,
            'file_type' => $extension,
        ]);

        // Créer les traductions
        DocumentTranslation::create([
            'document_id' => $document->id,
            'locale' => 'fr',
            'title' => $validated['title_fr'],
        ]);

        DocumentTranslation::create([
            'document_id' => $document->id,
            'locale' => 'en',
            'title' => $validated['title_en'],
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document partagé avec succès!');
    }

    /**
     * Télécharger un document
     */
    public function show(Document $document)
    {
        $filePath = $document->getFilePath();
        if (!file_exists($filePath)) {
            abort(404, 'Le fichier n\'existe pas.');
        }
        return response()->download($filePath, $document->original_filename);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Document $document)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if (!$document->isOwnedBy(Auth::user())) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce document.');
        }

        $translations = $document->translations->keyBy('locale');

        return view('documents.edit', compact('document', 'translations'));
    }

    /**
     * Mettre à jour un document
     */
    public function update(Request $request, Document $document)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if (!$document->isOwnedBy(Auth::user())) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce document.');
        }

        $validated = $request->validate([
            'title_fr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,zip,doc,docx|max:10240',
        ], [
            'title_fr.required' => 'Le titre en français est obligatoire.',
            'title_en.required' => 'Le titre en anglais est obligatoire.',
            'file.mimes' => 'Le fichier doit être au format PDF, ZIP, DOC ou DOCX.',
            'file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        // Si un nouveau fichier est téléchargé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            $document->deleteFile();

            // Télécharger le nouveau fichier
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $filePath = $file->storeAs('documents', $filename, 'public');

            $document->update([
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'file_path' => $filePath,
                'file_type' => $extension,
            ]);
        }

        // Mettre à jour les traductions
        $document->translations()->where('locale', 'fr')->update([
            'title' => $validated['title_fr'],
        ]);

        $document->translations()->where('locale', 'en')->update([
            'title' => $validated['title_en'],
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document mis à jour avec succès!');
    }

    /**
     * Supprimer un document
     */
    public function destroy(Document $document)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if (!$document->isOwnedBy(Auth::user())) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce document.');
        }

        // Supprimer le fichier du stockage
        $document->deleteFile();

        // Supprimer le document (les traductions seront supprimées en cascade)
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document supprimé avec succès!');
    }
}
