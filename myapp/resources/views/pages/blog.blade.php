<x-layouts.app :title="'Blog | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="'Stay updated with travel tips, destination stories, and multilingual insights from our founder team.'">
    <x-page-header
        eyebrow="Blog"
        title="Travel insights and stories"
        subtitle="Read the latest travel tips, destination features, and planning advice from our tourism experts."
        image="{{ asset('images/creation-africa/safari.jpg') }}"
        ctaUrl="{{ url("/{$currentLocale}/contact") }}"
        ctaText="Speak to an expert"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-glass-card class="space-y-12">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Blog</p>
                    <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">Travel insights for modern explorers</h1>
                    <p class="mt-4 max-w-2xl text-slate-600">
                        Find inspiration, practical travel advice, and seasonal highlights tailored for multilingual audiences.
                        Every post is focused on helping travelers plan with confidence and see the best of each destination.
                    </p>
                </div>

                @php
                    $blogs = \App\Models\Blog::where('is_published', true)->orderBy('published_at', 'desc')->get();
                @endphp

                @if($blogs->count() > 0)
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($blogs as $blog)
                            <a href="{{ route('blog.show', ['locale' => $currentLocale, 'slug' => $blog->slug]) }}" class="group rounded-3xl border border-slate-200 bg-white/80 shadow-lg shadow-slate-200/30 overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200/40">
                                @if($blog->featured_image)
                                    <div class="relative h-48 overflow-hidden bg-slate-100">
                                        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="h-full w-full object-cover transition-transform group-hover:scale-105" loading="lazy">
                                    </div>
                                @else
                                    <div class="flex h-48 items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                        <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <div class="flex items-center gap-2 text-sm text-slate-500">
                                        @if($blog->published_at)
                                            <time datetime="{{ $blog->published_at->toDateString() }}">
                                                {{ $blog->published_at->format('M d, Y') }}
                                            </time>
                                        @endif
                                    </div>
                                    <h2 class="mt-3 text-xl font-semibold text-slate-950 group-hover:text-[var(--color-accent)] transition-colors">{{ $blog->title }}</h2>
                                    @if($blog->excerpt)
                                        <p class="mt-2 text-slate-600 line-clamp-2">{{ Str::limit($blog->excerpt, 150) }}</p>
                                    @else
                                        <p class="mt-2 text-slate-600 line-clamp-2">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                                    @endif
                                    <div class="mt-4">
                                        <span class="inline-block text-[var(--color-accent)] font-semibold group-hover:opacity-70 transition-opacity">
                                            Read more →
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-12 text-center">
                        <p class="text-slate-600">No blog posts available yet. Check back soon!</p>
                    </div>
                @endif
            </x-glass-card>
        </div>
    </section>
</x-layouts.app>
