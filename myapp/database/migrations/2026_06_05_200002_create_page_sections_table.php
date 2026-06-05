<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_page_id')->nullable();
            $table->string('page_key')->nullable();
            $table->string('section_type');
            $table->json('content')->nullable();
            $table->text('custom_css')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('custom_page_id')->references('id')->on('custom_pages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
