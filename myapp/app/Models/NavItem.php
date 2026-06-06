<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'url',
        'reference_id',
        'parent_id',
        'sort_order',
        'is_active',
        'open_in_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(NavItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    public function getDisplayLabelAttribute(): string
    {
        if ($this->label) {
            return $this->label;
        }
        if ($this->type === 'category' && $this->relationLoaded('category') && $this->category) {
            return $this->category->name;
        }
        if ($this->type === 'destinations_hub') {
            return 'Destinations';
        }
        return 'Nav Item';
    }
}
