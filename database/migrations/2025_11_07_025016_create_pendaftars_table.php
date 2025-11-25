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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jalur_pendaftaran_id')->constrained();
            $table->foreignId('gelombang_id')->constrained();

            $table->string('program_studi_id_1', 5);
            $table->string('program_studi_id_2', 5)->nullable();

            $table->foreign('program_studi_id_1')
                ->references('kode_prodi')
                ->on('program_studis');

            $table->foreign('program_studi_id_2')
                ->references('kode_prodi')
                ->on('program_studis')
                ->onDelete('set null');

            
            $table->char('province_id', 2)->nullable();
            $table->char('regency_id', 4)->nullable();
            $table->char('district_id', 7)->nullable();
            $table->char('village_id', 10)->nullable();
            
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('regency_id')->references('id')->on('regencies');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('village_id')->references('id')->on('villages');
         
            

            $table->string('no_pendaftaran')->unique()->nullable();
            $table->string('status')->default('baru');

            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('nisn');
            $table->string('email');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('agama');
            $table->text('alamat_lengkap')->comment('untuk nama jalan, dusun, dsb.');;
            $table->string('rw',3)->nullable();
            $table->string('rt',3)->nullable();
            $table->string('no_hp');
            $table->string('asal_sekolah');
            $table->string('jurusan_asal_sekolah');
            $table->string('nama_ibu_kandung');
            $table->string('nama_ayah_kandung');

            $table->decimal('nilai_rapor_x_1')->nullable()->comment('nilai rata-rata rapor >=90');
            $table->decimal('nilai_rapor_x_2')->nullable()->comment('nilai rata-rata rapor >=90');
            $table->decimal('nilai_rapor_xi_1')->nullable()->comment('nilai rata-rata rapor >=90');
            $table->decimal('nilai_rapor_xi_2')->nullable()->comment('nilai rata-rata rapor >=90');

            $table->text('prestasi_non_akademik')->nullable();

            $table->integer('jumlah_hafalan_juz')->nullable();

            $table->string('nomor_peserta_utbk')->nullable();    

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
