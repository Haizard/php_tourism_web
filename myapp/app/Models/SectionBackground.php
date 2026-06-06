<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SectionBackground extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'section_name',
        'bg_type',
        'bg_value',
        'overlay_opacity',
        'text_color',
    ];

    protected $casts = [
        'overlay_opacity' => 'float',
    ];

    public function getBgUrlAttribute(): ?string
    {
        if ($this->bg_type !== 'image' || ! $this->bg_value) {
            return null;
        }

        if (str_starts_with($this->bg_value, 'http')) {
            return $this->bg_value;
        }

        return Storage::url($this->bg_value);
    }

    public function getInlineStyleAttribute(): string
    {
        if ($this->bg_type === 'color' && $this->bg_value) {
            return "background-color:{$this->bg_value};";
        }
        if ($this->bg_type === 'image' && $this->bg_url) {
            return "background-image:url('{$this->bg_url}');background-size:cover;background-position:center;";
        }

        return '';
    }

    public function getOverlayStyleAttribute(): string
    {
        if ($this->bg_type === 'image' && $this->overlay_opacity > 0) {
            return "background-color:rgba(0,0,0,{$this->overlay_opacity});";
        }

        return '';
    }
}
