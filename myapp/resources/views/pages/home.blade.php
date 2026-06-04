<x-layouts.app :title="$generalSettings->siteName ?? 'Tourism Starter Kit'" :description="$generalSettings->tagline ?? 'A multilingual, admin-ready foundation for travel agencies, tour operators, and destination brands.'">
    @include('sections.hero')
    @include('sections.featured-tours')
    @include('sections.destinations')
    @include('sections.about')
    @include('sections.statistics')
    @include('sections.testimonials')
    @include('sections.gallery')
    @include('sections.blogs')
    @include('sections.faq')
    @include('sections.newsletter')
    @include('sections.contact')
</x-layouts.app>
