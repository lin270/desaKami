<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->string('title', 150)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });

        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete();
            $table->enum('type', ['pendapatan', 'belanja', 'pembiayaan_penerimaan', 'pembiayaan_pengeluaran']);
            $table->string('category', 150)->nullable(); // kelompok pendapatan / bidang belanja
            $table->string('name', 200);
            $table->unsignedBigInteger('amount_plan')->default(0);     // anggaran (Rp)
            $table->unsignedBigInteger('amount_realized')->default(0); // realisasi (Rp)
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['budget_id', 'type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budgets');
    }
};
