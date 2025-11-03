<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    protected $fillable = [
        'article_id',
        'locale',
        'title',
        'content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
