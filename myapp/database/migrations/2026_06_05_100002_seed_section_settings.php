<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('settings')->where('group', 'sections')->exists();
        if ($exists) {
            return;
        }

        $defaults = [
            'hero_bg_type' => 'none',
            'hero_bg_color' => '#0f172a',
            'hero_bg_image' => '',
            'featured_tours_bg_type' => 'color',
            'featured_tours_bg_color' => '#f8fafc',
            'featured_tours_bg_image' => '',
            'destinations_bg_type' => 'color',
            'destinations_bg_color' => '#ffffff',
            'destinations_bg_image' => '',
            'about_bg_type' => 'color',
            'about_bg_color' => '#f1f5f9',
            'about_bg_image' => '',
            'statistics_bg_type' => 'color',
            'statistics_bg_color' => '#ffffff',
            'statistics_bg_image' => '',
            'testimonials_bg_type' => 'color',
            'testimonials_bg_color' => '#f8fafc',
            'testimonials_bg_image' => '',
            'gallery_bg_type' => 'color',
            'gallery_bg_color' => '#ffffff',
            'gallery_bg_image' => '',
            'blogs_bg_type' => 'color',
            'blogs_bg_color' => '#f1f5f9',
            'blogs_bg_image' => '',
            'faq_bg_type' => 'color',
            'faq_bg_color' => '#ffffff',
            'faq_bg_image' => '',
            'newsletter_bg_type' => 'color',
            'newsletter_bg_color' => '#f8fafc',
            'newsletter_bg_image' => '',
            'contact_bg_type' => 'color',
            'contact_bg_color' => '#ffffff',
            'contact_bg_image' => '',
        ];

        foreach ($defaults as $name => $payload) {
            DB::table('settings')->insert([
                'group' => 'sections',
                'name' => $name,
                'locked' => false,
                'payload' => json_encode($payload),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('group', 'sections')->delete();
    }
};
