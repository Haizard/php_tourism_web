<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            'group'   => 'homepage',
            'name'    => 'sections',
            'payload' => json_encode([
                ['key' => 'hero',           'label' => 'Hero Banner',       'is_active' => true],
                ['key' => 'featured_tours', 'label' => 'Featured Tours',    'is_active' => true],
                ['key' => 'destinations',   'label' => 'Destinations',      'is_active' => true],
                ['key' => 'about',          'label' => 'About / Features',  'is_active' => true],
                ['key' => 'statistics',     'label' => 'Statistics',        'is_active' => true],
                ['key' => 'testimonials',   'label' => 'Testimonials',      'is_active' => true],
                ['key' => 'gallery',        'label' => 'Gallery',           'is_active' => true],
                ['key' => 'blogs',          'label' => 'Blog / Journal',    'is_active' => true],
                ['key' => 'faq',            'label' => 'FAQ',               'is_active' => true],
                ['key' => 'newsletter',     'label' => 'Newsletter',        'is_active' => true],
                ['key' => 'contact',        'label' => 'Contact',           'is_active' => true],
            ]),
            'locked' => false,
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('group', 'homepage')->delete();
    }
};
