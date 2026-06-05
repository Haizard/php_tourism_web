@php
    use App\Models\Blog;
    $limit = (int) ($c['limit'] ?? 6);
    $blogs = Blog::where('is_published', true)->latest()->limit($limit)->get();
@endphp

<section class="py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if(!empty($c['title']) || !empty($c['subtitle']))
            <div class="text-center mb-12">
                @if(!empty($c['title']))
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{{ $c['title'] }}</h2>
                @endif
                @if(!empty($c['subtitle']))
                    <p class="mt-4 text-lg text-slate-500">{{ $c['subtitle'] }}</p>
                @endif
            </div>
        @endif

        @if($blogs->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($blogs as $post)
                    <a href="{{ url("/{$currentLocale}/blog/{$post->slug}") }}"
                       class="group block rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100 transition hover:-translate-y-1 duration-200">
                        @if($post->featured_image)
                            <div class="overflow-hidden">
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-48 object-cover transition duration-300 group-hover:scale-105" />
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 text-base mb-1">{{ $post->title }}</h3>
                            <p class="text-slate-500 text-sm line-clamp-2">{{ $post->excerpt }}</p>
                            <p class="mt-3 text-xs text-slate-400">{{ $post->published_at?->diffForHumans() ?? '' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-slate-400">No blog posts yet.</p>
        @endif
    </div>
</section>
