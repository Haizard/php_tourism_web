<x-layouts.app :title="$generalSettings->siteName ?? 'Tourism Starter Kit'" :description="$generalSettings->tagline ?? 'A multilingual, admin-ready foundation for travel agencies, tour operators, and destination brands.'">

    @php
        $homeSections = $homePageSettings->sections ?? [
            ['key' => 'hero',           'is_active' => true],
            ['key' => 'featured_tours', 'is_active' => true],
            ['key' => 'destinations',   'is_active' => true],
            ['key' => 'about',          'is_active' => true],
            ['key' => 'statistics',     'is_active' => true],
            ['key' => 'testimonials',   'is_active' => true],
            ['key' => 'gallery',        'is_active' => true],
            ['key' => 'blogs',          'is_active' => true],
            ['key' => 'faq',            'is_active' => true],
            ['key' => 'newsletter',     'is_active' => true],
            ['key' => 'contact',        'is_active' => true],
        ];
    @endphp

    @foreach($homeSections as $homeSection)
        @if($homeSection['is_active'] ?? true)
            @php
                $sectionView = 'sections.' . str_replace('_', '-', $homeSection['key']);
            @endphp
            @if(View::exists($sectionView))
                @include($sectionView)
            @endif
        @endif
    @endforeach

</x-layouts.app>
