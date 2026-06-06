<?php

namespace Database\Seeders;

use App\Models\SectionBackground;
use Illuminate\Database\Seeder;

class SectionBackgroundSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_key' => 'hero',           'section_name' => 'Hero Banner'],
            ['section_key' => 'featured_tours', 'section_name' => 'Featured Tours'],
            ['section_key' => 'destinations',   'section_name' => 'Top Destinations'],
            ['section_key' => 'about',          'section_name' => 'About / Commitments'],
            ['section_key' => 'statistics',     'section_name' => 'Statistics'],
            ['section_key' => 'testimonials',   'section_name' => 'Testimonials'],
            ['section_key' => 'gallery',        'section_name' => 'Gallery'],
            ['section_key' => 'blogs',          'section_name' => 'Blog / Journal'],
            ['section_key' => 'faq',            'section_name' => 'FAQ'],
            ['section_key' => 'newsletter',     'section_name' => 'Newsletter'],
            ['section_key' => 'contact',        'section_name' => 'Contact'],
        ];

        foreach ($sections as $section) {
            SectionBackground::updateOrInsert(
                ['section_key' => $section['section_key']],
                array_merge($section, [
                    'bg_type'         => 'none',
                    'bg_value'        => null,
                    'overlay_opacity' => 0.00,
                    'text_color'      => 'dark',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ])
            );
        }
    }
}
