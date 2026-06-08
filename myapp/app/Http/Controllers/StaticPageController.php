<?php

namespace App\Http\Controllers;

use App\Models\SectionBackground;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.about', compact('sectionBackgrounds'));
    }

    public function destinations(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.destinations', compact('sectionBackgrounds'));
    }

    public function blog(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.blog', compact('sectionBackgrounds'));
    }

    public function gallery(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.gallery', compact('sectionBackgrounds'));
    }

    public function faq(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.faq', compact('sectionBackgrounds'));
    }

    public function contact(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.contact', compact('sectionBackgrounds'));
    }

    public function privacy(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.privacy', compact('sectionBackgrounds'));
    }

    public function terms(): View
    {
        $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        return view('pages.terms', compact('sectionBackgrounds'));
    }
}
