<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\DetailTemplate;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $blogTemplate = DetailTemplate::where('page_type', 'blog_detail')->first();

        return view('pages.blogs-detail', [
            'blog'         => $blog,
            'blogTemplate' => $blogTemplate,
            'currentLocale' => $locale,
        ]);
    }
}
