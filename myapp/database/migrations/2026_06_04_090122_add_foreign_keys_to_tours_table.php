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
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->onDelete('set null');
            $table->foreignId('destination_id')->nullable()->after('category_id')->constrained('destinations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('tours_category_id_foreign');
            $table->dropForeignKeyIfExists('tours_destination_id_foreign');
            $table->dropColumn(['category_id', 'destination_id']);
        });
    }
};
