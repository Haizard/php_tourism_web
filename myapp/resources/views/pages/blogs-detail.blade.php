<x-layouts.app :title="$blog->title . ' | ' . ($generalSettings->siteName ?? 'Tourism Starter Kit')" :description="$blog->excerpt ?? 'Read this blog post.'">
    <x-page-header
        :title="$blog->title"
        :subtitle="$blog->excerpt"
        :image="$blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('images/creation-africa/safari.jpg')"
        eyebrow="Blog Post"
        :ctaUrl="url('/' . $currentLocale . '/blog')"
        ctaText="Back to Blog"
    />

    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="grid gap-12 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <x-glass-card class="space-y-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                @if($blog->published_at)
                                    <time datetime="{{ $blog->published_at->toDateString() }}">
                                        {{ $blog->published_at->format('F d, Y') }}
                                    </time>
                                @endif
                                <span>•</span>
                                <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
                            </div>
                            <h1 class="text-4xl font-bold text-slate-950">{{ $blog->title }}</h1>
                        </div>

                        <div class="prose prose-slate max-w-none">
                            {!! $blog->content !!}
                        </div>

                        <div class="border-t border-slate-200 pt-8">
                            <a href="{{ url('/' . $currentLocale . '/blog') }}" class="inline-flex items-center gap-2 text-[var(--color-accent)] font-semibold hover:opacity-70 transition-opacity">
                                ← Back to Blog
                            </a>
                        </div>
                    </x-glass-card>
                </div>

                <div class="lg:col-span-1">
                    <x-glass-card class="space-y-6 sticky top-8">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-950">Article Details</h3>
                        </div>

                        <div class="space-y-4">
                            @if($blog->published_at)
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Published</p>
                                    <p class="mt-1 text-slate-600">{{ $blog->published_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm font-semibold text-slate-950">Status</p>
                                <p class="mt-1">
                                    @if($blog->is_published)
                                        <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Published</span>
                                    @else
                                        <span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">Draft</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-950">Reading Time</p>
                                <p class="mt-1 text-slate-600">{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} minutes</p>
                            </div>
                        </div>
                    </x-glass-card>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
