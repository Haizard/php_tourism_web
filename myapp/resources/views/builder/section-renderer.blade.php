@php
    $sectionId  = 'pb-section-' . $section->id;
    $sectionType = $section->section_type;
    $c = is_array($section->content) ? $section->content : [];
    $customCss = $section->custom_css ?? '';

    // Build background style
    $bgStyle = '';
    $bgType  = $c['bg_type'] ?? 'none';
    if ($bgType === 'color' && !empty($c['bg_color'])) {
        $bgStyle = 'background-color: ' . e($c['bg_color']) . ';';
    } elseif ($bgType === 'image' && !empty($c['bg_image'])) {
        $bgStyle = 'background-image: url(' . asset('storage/' . $c['bg_image']) . '); background-size: cover; background-position: center;';
    }
@endphp

@if($customCss)
<style>
    #{{ $sectionId }} { {{ $customCss }} }
</style>
@endif

<div id="{{ $sectionId }}" class="pb-section pb-section--{{ $sectionType }}" style="{{ $bgStyle }}">
    @switch($sectionType)
        @case('hero')        @include('builder.sections.hero',         ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('text_block')  @include('builder.sections.text-block',   ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('image_text')  @include('builder.sections.image-text',   ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('cards_grid')  @include('builder.sections.cards-grid',   ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('stats')       @include('builder.sections.stats',        ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('cta')         @include('builder.sections.cta',          ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('gallery_block') @include('builder.sections.gallery-block', ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('html_block')  @include('builder.sections.html-block',   ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('tours_list')  @include('builder.sections.tours-list',   ['c' => $c, 'sectionId' => $sectionId]) @break
        @case('blogs_list')  @include('builder.sections.blogs-list',   ['c' => $c, 'sectionId' => $sectionId]) @break
    @endswitch
</div>
