<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        return view('pages.blogs-detail', [
            'blog' => $blog,
            'currentLocale' => $locale,
        ]);
    }
}
