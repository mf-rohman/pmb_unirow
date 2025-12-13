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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); 
            $table->string('nama_guru_bk')->nullable(); 
            $table->string('asal_sekolah')->nullable(); 
    
            $table->boolean('is_used')->default(false); 
            $table->timestamp('used_at')->nullable(); 
            $table->foreignId('pendaftar_id')->nullable()->constrained('pendaftars')->onDelete('set null');
            $table->date('expired_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
