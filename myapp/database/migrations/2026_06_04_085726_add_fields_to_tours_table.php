<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('price');
            $table->decimal('discount_price', 8, 2)->nullable()->after('price');
            $table->json('itinerary')->nullable()->after('content');
            $table->json('included_services')->nullable()->after('itinerary');
            $table->json('excluded_services')->nullable()->after('included_services');
            $table->string('seo_meta_title')->nullable()->after('slug');
            $table->text('seo_meta_description')->nullable()->after('seo_meta_title');
            $table->string('seo_keywords')->nullable()->after('seo_meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['duration', 'discount_price', 'itinerary', 'included_services', 'excluded_services', 'seo_meta_title', 'seo_meta_description', 'seo_keywords']);
        });
    }
};
