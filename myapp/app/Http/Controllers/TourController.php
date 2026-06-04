<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\View\View;

class TourController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $tour = Tour::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        return view('pages.tours-detail', [
            'tour' => $tour,
            'currentLocale' => $locale,
        ]);
    }
}
