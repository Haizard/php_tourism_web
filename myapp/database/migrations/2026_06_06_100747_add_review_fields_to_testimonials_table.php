<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('review_title')->nullable()->after('author_name');
            $table->string('traveler_type')->nullable()->after('rating');
            $table->date('visit_date')->nullable()->after('traveler_type');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['review_title', 'traveler_type', 'visit_date']);
        });
    }
};
