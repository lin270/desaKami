<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            // Foreign key ke gallery_images ditambahkan di migration 000007
            $table->unsignedBigInteger('cover_image_id')->nullable();
            $table->date('taken_at')->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'taken_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
