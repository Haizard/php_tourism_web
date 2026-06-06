<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_templates', function (Blueprint $table) {
            $table->json('sections_config')->nullable()->after('visible_sections');
        });

        DB::table('detail_templates')
            ->where('page_type', 'tour_detail')
            ->update([
                'sections_config' => json_encode([
                    'main' => [
                        ['key' => 'overview',          'label' => 'Tour Overview',      'is_active' => true],
                        ['key' => 'itinerary',         'label' => 'Itinerary',          'is_active' => true],
                        ['key' => 'included_services', 'label' => 'Included Services',  'is_active' => true],
                        ['key' => 'excluded_services', 'label' => 'Excluded Services',  'is_active' => true],
                    ],
                    'sidebar' => [
                        ['key' => 'tour_details_card', 'label' => 'Tour Details Card', 'is_active' => true],
                        ['key' => 'booking_form',      'label' => 'Booking Form',       'is_active' => true],
                    ],
                    'below' => [
                        ['key' => 'related_tours', 'label' => 'Related Tours', 'is_active' => true],
                    ],
                ]),
            ]);

        DB::table('detail_templates')
            ->where('page_type', 'blog_detail')
            ->update([
                'sections_config' => json_encode([
                    'main' => [
                        ['key' => 'content',      'label' => 'Article Content',      'is_active' => true],
                        ['key' => 'social_share', 'label' => 'Social Share Buttons', 'is_active' => true],
                    ],
                    'sidebar' => [
                        ['key' => 'article_details', 'label' => 'Article Details', 'is_active' => true],
                    ],
                    'below' => [
                        ['key' => 'related_posts', 'label' => 'Related Posts', 'is_active' => true],
                    ],
                ]),
            ]);
    }

    public function down(): void
    {
        Schema::table('detail_templates', function (Blueprint $table) {
            $table->dropColumn('sections_config');
        });
    }
};
