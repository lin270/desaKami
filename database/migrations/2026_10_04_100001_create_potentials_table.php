<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('potentials', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug')->unique();
            $table->enum('category', ['umkm', 'pertanian', 'wisata', 'produk_unggulan']);
            $table->string('summary', 300)->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('location')->nullable();
            // Kontak ditampilkan PUBLIK. Isi hanya dengan persetujuan pemilik usaha.
            $table->string('contact', 150)->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'category', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potentials');
    }
};
