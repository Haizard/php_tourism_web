<?php

namespace App\Http\Controllers;

use App\Models\DetailTemplate;
use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\View\View;

class TourController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $tour = Tour::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $tourTemplate = DetailTemplate::where('page_type', 'tour_detail')->first();

        $reviews = Testimonial::where('tour_id', $tour->id)
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->get();

        $avgRating     = $reviews->avg('rating') ?? 0;
        $reviewCount   = $reviews->count();
        $ratingCounts  = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $reviews->where('rating', $i)->count();
        }

        return view('pages.tours-detail', [
            'tour'          => $tour,
            'tourTemplate'  => $tourTemplate,
            'currentLocale' => $locale,
            'reviews'       => $reviews,
            'avgRating'     => round($avgRating, 1),
            'reviewCount'   => $reviewCount,
            'ratingCounts'  => $ratingCounts,
        ]);
    }
}
