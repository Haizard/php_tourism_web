<?php

namespace App\Http\Controllers;

use App\Models\SectionBackground;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');

        return view('pages.home', compact('sectionBackgrounds'));
    }
}
