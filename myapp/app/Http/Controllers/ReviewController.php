<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, string $locale, int $tour): RedirectResponse
    {
        $tourModel = Tour::findOrFail($tour);

        $validated = $request->validate([
            'author_name'   => 'required|string|max:100',
            'review_title'  => 'required|string|max:150',
            'content'       => 'required|string|min:20|max:2000',
            'rating'        => 'required|integer|min:1|max:5',
            'traveler_type' => 'nullable|in:solo,couple,family,friends,business',
            'visit_date'    => 'nullable|date|before_or_equal:today',
        ]);

        Testimonial::create([
            'tour_id'       => $tourModel->id,
            'author_name'   => $validated['author_name'],
            'review_title'  => $validated['review_title'],
            'content'       => $validated['content'],
            'rating'        => $validated['rating'],
            'traveler_type' => $validated['traveler_type'] ?? null,
            'visit_date'    => $validated['visit_date'] ?? null,
            'is_published'  => false,
        ]);

        return redirect()
            ->back()
            ->with('review_success', 'Thank you for your review! It will appear after approval.');
    }
}
