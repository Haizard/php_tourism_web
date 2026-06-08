<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\DetailTemplate;
use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(Request $request, string $locale): View
    {
        $query = Tour::with(['category', 'destination'])
            ->where('is_published', true);

        $q        = trim($request->get('q', ''));
        $category = $request->get('category', '');
        $duration = $request->get('duration', '');
        $maxPrice = $request->get('max_price', '');
        $minPrice = $request->get('min_price', '');

        if ($q !== '') {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('excerpt', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category !== '') {
            $query->whereHas('category', fn ($qb) => $qb->where('slug', $category));
        }

        if ($duration !== '') {
            $query->where('duration', 'like', "%{$duration}%");
        }

        if ($maxPrice !== '' && is_numeric($maxPrice)) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($minPrice !== '' && is_numeric($minPrice)) {
            $query->where('price', '>=', (float) $minPrice);
        }

        $tours = $query->orderBy('title')->get();

        $categories   = Category::orderBy('name')->get();
        $destinations = Destination::where('is_published', true)->orderBy('name')->get();

        $durations = Tour::where('is_published', true)
            ->whereNotNull('duration')
            ->distinct()
            ->pluck('duration')
            ->sort()
            ->values();

        $priceRange = Tour::where('is_published', true)->selectRaw('min(price) as min_p, max(price) as max_p')->first();

        return view('pages.tours', compact(
            'tours',
            'categories',
            'destinations',
            'durations',
            'priceRange',
            'q',
            'category',
            'duration',
            'maxPrice',
            'minPrice',
        ));
    }

    public function show(string $locale, string $slug): View
    {
        $tour = Tour::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $tourTemplate = DetailTemplate::where('page_type', 'tour_detail')->first();

        $reviews = Testimonial::where('tour_id', $tour->id)
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->get();

        $avgRating    = $reviews->avg('rating') ?? 0;
        $reviewCount  = $reviews->count();
        $ratingCounts = [];
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

    public function showDestination(string $locale, string $slug): View
    {
        $destination = Destination::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        // Get tours for this destination
        $tours = Tour::where('destination_id', $destination->id)
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('pages.destination-show', [
            'destination' => $destination,
            'tours' => $tours,
            'currentLocale' => $locale,
        ]);
    }
}
