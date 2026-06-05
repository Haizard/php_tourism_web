<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $fillable = [
        'custom_page_id',
        'page_key',
        'section_type',
        'content',
        'custom_css',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content'   => 'array',
        'is_active' => 'boolean',
    ];

    public function customPage(): BelongsTo
    {
        return $this->belongsTo(CustomPage::class);
    }
}
