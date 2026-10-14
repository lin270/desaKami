<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Daftar berita per kategori: WHERE category_id AND status ORDER BY published_at
            $table->index(['category_id', 'status', 'published_at'], 'posts_category_status_published_idx');
            // Pencarian headline beranda
            $table->index(['is_featured', 'status', 'published_at'], 'posts_featured_status_published_idx');
        });

        Schema::table('announcements', function (Blueprint $table) {
            // Pengumuman penting di atas, lalu terbaru
            $table->index(['status', 'is_important', 'published_at'], 'announcements_status_important_idx');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_category_status_published_idx');
            $table->dropIndex('posts_featured_status_published_idx');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_status_important_idx');
        });
    }
};
