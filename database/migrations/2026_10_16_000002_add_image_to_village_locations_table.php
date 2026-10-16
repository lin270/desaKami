<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Foto per lokasi peta. Kolom nullable: data lokasi lama tetap utuh. */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('village_locations') && ! Schema::hasColumn('village_locations', 'image')) {
            Schema::table('village_locations', function (Blueprint $table) {
                $table->string('image')->nullable()->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('village_locations', 'image')) {
            Schema::table('village_locations', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
