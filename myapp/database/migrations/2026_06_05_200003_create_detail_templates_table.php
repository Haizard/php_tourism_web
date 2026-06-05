<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->unique();
            $table->string('layout')->default('default');
            $table->string('header_style')->default('default');
            $table->string('card_style')->default('default');
            $table->json('visible_sections')->nullable();
            $table->text('custom_css')->nullable();
            $table->timestamps();
        });

        DB::table('detail_templates')->insert([
            ['page_type' => 'tour_detail', 'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default', 'visible_sections' => json_encode(['gallery' => true, 'booking_form' => true, 'related_tours' => true, 'itinerary' => true, 'map' => false]), 'custom_css' => '', 'created_at' => now(), 'updated_at' => now()],
            ['page_type' => 'blog_detail', 'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default', 'visible_sections' => json_encode(['author_bio' => true, 'related_posts' => true, 'social_share' => true]), 'custom_css' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_templates');
    }
};
