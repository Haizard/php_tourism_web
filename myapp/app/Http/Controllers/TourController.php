<?php

namespace App\Http\Controllers;

use App\Models\DetailTemplate;
use App\Models\Tour;
use Illuminate\View\View;

class TourController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $tour = Tour::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $tourTemplate = DetailTemplate::where('page_type', 'tour_detail')->first();

        return view('pages.tours-detail', [
            'tour'         => $tour,
            'tourTemplate' => $tourTemplate,
            'currentLocale' => $locale,
        ]);
    }
}
