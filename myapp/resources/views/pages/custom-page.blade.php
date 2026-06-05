<x-layouts.app
    :title="$page->seo_meta_title ?: $page->title"
    :description="$page->seo_meta_description ?: ''">

    @if($page->custom_css)
    <style>
        {!! $page->custom_css !!}
    </style>
    @endif

    @foreach($sections as $section)
        @include('builder.section-renderer', ['section' => $section])
    @endforeach

</x-layouts.app>
