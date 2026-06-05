<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'custom_css',
        'seo_meta_title',
        'seo_meta_description',
        'seo_keywords',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'custom_page_id')->orderBy('sort_order');
    }
}
