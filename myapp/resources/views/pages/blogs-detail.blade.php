<x-layouts.app :title="$blog->title . ' | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$blog->excerpt ?? 'Read this blog post.'">

    @if(!empty($blogTemplate?->custom_css))
    <style>
        {!! $blogTemplate->custom_css !!}
    </style>
    @endif

    @php
        $headerStyle = $blogTemplate?->header_style ?? 'default';
        $layout      = $blogTemplate?->layout ?? 'default';
        $cardStyle   = $blogTemplate?->card_style ?? 'default';
        $visible     = $blogTemplate?->visible_sections ?? [];
        $cardClass   = match($cardStyle) {
            'bordered' => 'border border-slate-200 rounded-2xl p-6 bg-white',
            'shadow'   => 'rounded-2xl p-6 bg-white shadow-xl',
            'glass'    => 'rounded-2xl p-6 bg-white/30 backdrop-blur border border-white/20',
            default    => 'rounded-2xl p-6 bg-white/60 backdrop-blur border border-slate-100 shadow-sm',
        };
    @endphp

    @if($headerStyle === 'minimal')
        <div class="border-b border-slate-200 bg-white py-12 px-6">
            <div class="mx-auto max-w-4xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Blog Post</p>
                <h1 class="text-4xl font-black text-slate-900">{{ $blog->title }}</h1>
                @if($blog->excerpt)<p class="mt-3 text-lg text-slate-500">{{ $blog->excerpt }}</p>@endif
            </div>
        </div>
    @elseif($headerStyle === 'split')
        <div class="bg-slate-900 py-16 px-6">
            <div class="mx-auto max-w-6xl grid gap-10 lg:grid-cols-2 items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Blog Post</p>
                    <h1 class="text-4xl font-black text-white">{{ $blog->title }}</h1>
                    @if($blog->excerpt)<p class="mt-3 text-lg text-slate-300">{{ $blog->excerpt }}</p>@endif
                </div>
                @if($blog->featured_image)
                    <img src="{{ asset('storage/'.$blog->featured_image) }}" class="rounded-2xl w-full h-64 object-cover shadow-xl" alt="{{ $blog->title }}" />
                @endif
            </div>
        </div>
    @elseif($headerStyle === 'overlay')
        <div class="relative overflow-hidden min-h-[320px] flex items-end">
            <div class="absolute inset-0" style="background-image: url({{ $blog->featured_image ? asset('storage/'.$blog->featured_image) : asset('images/creation-africa/safari.jpg') }}); background-size:cover; background-position:center;"></div>
            <div class="absolute inset-0 bg-slate-950/70"></div>
            <div class="relative mx-auto max-w-4xl w-full px-6 pb-12">
                <p class="text-sm font-semibold uppercase tracking-wider text-[var(--color-primary)] mb-2">Blog Post</p>
                <h1 class="text-4xl font-black text-white">{{ $blog->title }}</h1>
                @if($blog->excerpt)<p class="mt-3 text-lg text-white/80">{{ $blog->excerpt }}</p>@endif
            </div>
        </div>
    @else
        <x-page-header
            :title="$blog->title"
            :subtitle="$blog->excerpt"
            :image="$blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('images/creation-africa/safari.jpg')"
            eyebrow="Blog Post"
            :ctaUrl="url('/' . $currentLocale . '/blog')"
            ctaText="Back to Blog"
        />
    @endif

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto {{ $layout === 'centered' ? 'max-w-3xl' : 'max-w-4xl' }}">
            <div class="{{ $layout === 'full_width' ? '' : 'grid gap-12 lg:grid-cols-3' }}">

                <div class="{{ $layout === 'full_width' ? '' : 'lg:col-span-2' }}">
                    <div class="{{ $cardClass }} space-y-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                @if($blog->published_at)
                                    <time datetime="{{ $blog->published_at->toDateString() }}">{{ $blog->published_at->format('F d, Y') }}</time>
                                    <span>•</span>
                                @endif
                                <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                            </div>
                            <h1 class="text-4xl font-bold text-slate-950">{{ $blog->title }}</h1>
                        </div>
                        <div class="prose prose-slate max-w-none">{!! $blog->content !!}</div>

                        @if($visible['social_share'] ?? true)
                        <div class="border-t border-slate-200 pt-6 flex items-center gap-4">
                            <span class="text-sm font-semibold text-slate-500">Share:</span>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="text-slate-400 hover:text-sky-500 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-slate-400 hover:text-blue-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></a>
                        </div>
                        @endif

                        <div class="border-t border-slate-200 pt-8">
                            <a href="{{ url('/' . $currentLocale . '/blog') }}" class="inline-flex items-center gap-2 text-[var(--color-accent)] font-semibold hover:opacity-70 transition-opacity">← Back to Blog</a>
                        </div>
                    </div>
                </div>

                @if($layout !== 'full_width')
                <div class="lg:col-span-1">
                    <div class="{{ $cardClass }} space-y-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-slate-950">Article Details</h3>
                        <div class="space-y-4">
                            @if($blog->published_at)<div><p class="text-sm font-semibold text-slate-950">Published</p><p class="mt-1 text-slate-600">{{ $blog->published_at->format('F d, Y') }}</p></div>@endif
                            <div><p class="text-sm font-semibold text-slate-950">Reading Time</p><p class="mt-1 text-slate-600">{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} minutes</p></div>
                            @if($blog->is_published)
                                <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Published</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

    @if($visible['related_posts'] ?? true)
        @php
            $relatedPosts = \App\Models\Blog::where('is_published', true)
                ->where('id', '!=', $blog->id)
                ->latest()->limit(3)->get();
        @endphp
        @if($relatedPosts->count())
        <section class="px-6 pb-20 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <h2 class="text-2xl font-bold text-slate-900 mb-8">Related Posts</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        <a href="{{ url("/{$currentLocale}/blog/{$related->slug}") }}" class="group block rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100 transition hover:-translate-y-1">
                            @if($related->featured_image)<img src="{{ asset('storage/'.$related->featured_image) }}" class="w-full h-40 object-cover group-hover:scale-105 transition" alt="{{ $related->title }}" />@endif
                            <div class="p-4"><h3 class="font-bold text-slate-900 text-sm">{{ $related->title }}</h3><p class="text-xs text-slate-400 mt-1">{{ $related->published_at?->diffForHumans() }}</p></div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endif

</x-layouts.app>
