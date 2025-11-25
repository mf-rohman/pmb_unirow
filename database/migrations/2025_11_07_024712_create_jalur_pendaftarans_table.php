<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jalur_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jalur')->unique();
            $table->string('nama_jalur');
            $table->text('deskripsi')->nullable();

            $table->string('kategori');

            $table->boolean('bebas_tes_tulis')->default(false);
            $table->boolean('free_biaya_pendaftaran')->default(false);
            $table->boolean('free_daftar_ulang')->default(false);
            $table->boolean('free_dpp')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_pendaftarans');
    }
};
