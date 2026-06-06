<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function show(string $slug): View
    {
        $destination = Destination::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $tours = $destination->tours()->where('is_published', true)->get();

        return view('pages.destination-show', compact('destination', 'tours'));
    }
}
