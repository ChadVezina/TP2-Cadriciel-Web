<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected DocumentService $documentService)
    {
    }

    /**
     * Display a listing of the documents with pagination.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Document::class);

        $documents = $this->documentService->getPaginatedDocuments(10);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        $this->authorize('create', Document::class);

        return view('documents.create');
    }

    /**
     * Store a newly created document in storage.
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
     * Download the specified document.
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
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        $this->authorize('update', $document);

        $translations = $document->translations->keyBy('locale');

        return view('documents.edit', compact('document', 'translations'));
    }

    /**
     * Update the specified document in storage.
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
     * Remove the specified document from storage.
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
