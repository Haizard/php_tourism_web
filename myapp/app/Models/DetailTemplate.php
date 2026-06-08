<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTemplate extends Model
{
    protected $fillable = [
        'page_type',
        'layout',
        'header_style',
        'card_style',
        'visible_sections',
        'sections_config',
        'custom_css',
    ];

    protected $casts = [
        'visible_sections' => 'array',
        'sections_config'  => 'array',
    ];
}
