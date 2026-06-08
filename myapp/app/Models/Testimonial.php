<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'review_title',
        'content',
        'rating',
        'traveler_type',
        'visit_date',
        'author_image',
        'author_title',
        'tour_id',
        'is_published',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating'       => 'integer',
        'sort_order'   => 'integer',
        'published_at' => 'datetime',
        'visit_date'   => 'date',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function travelerTypeLabel(): string
    {
        return match ($this->traveler_type) {
            'solo'     => '🧳 Solo Traveler',
            'couple'   => '💑 Couple',
            'family'   => '👨‍👩‍👧 Family',
            'friends'  => '👫 Friends',
            'business' => '💼 Business',
            default    => '',
        };
    }
}
