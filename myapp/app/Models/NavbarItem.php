<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavbarItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'url',
        'type',
        'reference_id',
        'sort_order',
        'is_active',
        'open_in_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'reference_id' => 'integer',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'reference_id');
    }

    public function getResolvedLabelAttribute(): string
    {
        if ($this->label) {
            return $this->label;
        }
        if ($this->type === 'category' && $this->category) {
            return $this->category->name;
        }
        if ($this->type === 'destination' && $this->destination) {
            return $this->destination->name;
        }
        return 'Unnamed';
    }

    public function getDropdownItemsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->type === 'category' && $this->reference_id) {
            return Tour::where('category_id', $this->reference_id)
                ->where('is_published', true)
                ->orderBy('title')
                ->get();
        }
        if ($this->type === 'destination' && $this->reference_id) {
            return Tour::where('destination_id', $this->reference_id)
                ->where('is_published', true)
                ->orderBy('title')
                ->get();
        }
        return collect();
    }
}
