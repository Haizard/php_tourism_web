<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('image');
                $table->text('description')->nullable();
                $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('set null');
                $table->string('category')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('category');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
