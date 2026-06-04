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
        Schema::table('blogs', function (Blueprint $table) {
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
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['seo_meta_title', 'seo_meta_description', 'seo_keywords']);
        });
    }
};
