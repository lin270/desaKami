<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Ruang Aspirasi: kritik dan saran warga (tabel baru; tidak mengubah tabel lama). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->default('Warga');
            $table->text('message');
            $table->unsignedTinyInteger('color')->default(0);   // warna kertas catatan
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_hash', 64)->nullable();           // hash, bukan IP asli: untuk menelusuri penyalahgunaan
            $table->timestamps();
            $table->softDeletes();
            $table->index(['deleted_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirations');
    }
};
