<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('section_name');
            $table->enum('bg_type', ['none', 'color', 'image'])->default('none');
            $table->string('bg_value')->nullable();
            $table->decimal('overlay_opacity', 3, 2)->default(0.00);
            $table->enum('text_color', ['dark', 'light'])->default('dark');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_backgrounds');
    }
};
