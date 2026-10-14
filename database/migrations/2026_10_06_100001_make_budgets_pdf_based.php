<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * APBDes disederhanakan: satu file PDF per tahun (bukan rincian per baris).
 * Aman dijalankan di database lama: tabel rincian dibuang, kolom file ditambahkan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('budget_items');

        Schema::table('budgets', function (Blueprint $table) {
            $table->string('file')->nullable()->after('notes');           // path di storage/app/public
            $table->unsignedInteger('file_size')->nullable()->after('file'); // byte
        });
    }

    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn(['file', 'file_size']);
        });
    }
};
