<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('label');
            $table->string('value');
            $table->string('unit', 30)->nullable();
            $table->enum('group', ['penduduk', 'wilayah', 'lainnya'])->default('lainnya');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_statistics');
    }
};
