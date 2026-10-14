<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_items', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['hilang', 'ditemukan']);
            $table->string('title', 200);
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location');
            $table->date('event_date');
            $table->string('image')->nullable();
            // Kontak ditampilkan PUBLIK. Kosong = pengunjung diarahkan ke kantor desa.
            $table->string('contact', 150)->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_resolved', 'event_date']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_items');
    }
};
