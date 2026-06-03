@php($locale = $currentLocale ?? app()->getLocale())

<footer class="border-t border-white/20 bg-slate-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-12 md:grid-cols-3 lg:px-8">
        <div>
            <p class="text-lg font-black">Tourism Starter Kit</p>
            <p class="mt-3 max-w-sm text-sm leading-6 text-slate-300">
                A Laravel foundation for multilingual tourism websites, booking flows, and content-managed travel experiences.
            </p>
        </div>

        <div>
            <p class="font-bold">Explore</p>
            <div class="mt-3 grid gap-2 text-sm text-slate-300">
                <a href="{{ url("/{$locale}/tours") }}">Tours</a>
                <a href="{{ url("/{$locale}/destinations") }}">Destinations</a>
                <a href="{{ url("/{$locale}/gallery") }}">Gallery</a>
            </div>
        </div>

        <div>
            <p class="font-bold">Starter Status</p>
            <p class="mt-3 text-sm leading-6 text-slate-300">
                Foundation installed. Tourism modules will be added in future phases.
            </p>
        </div>
    </div>
</footer>
