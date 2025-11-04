<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTranslation extends Model
{
    protected $fillable = [
        'document_id',
        'locale',
        'title',
    ];

    /**
     * Relation avec le document
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
