<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;
use App\Models\PageSection;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    public function show(string $locale, string $slug)
    {
        $page = CustomPage::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $sections = $page->sections()->where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.custom-page', compact('page', 'sections'));
    }

    public function builtIn(string $locale, string $pageKey)
    {
        $sections = PageSection::where('page_key', $pageKey)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.built-in-sections', compact('pageKey', 'sections'));
    }
}
