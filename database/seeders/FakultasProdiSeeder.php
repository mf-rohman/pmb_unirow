<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Schema;

class FakultasProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Baris ini akan mengosongkan tabel terlebih dahulu untuk menghindari error duplikasi
        Schema::disableForeignKeyConstraints();
        ProgramStudi::truncate();
        Fakultas::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            [
                'kode_fakultas' => 'FKIP',
                'nama_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'program_studi' => [
                    ['kode_prodi' => '87205', 'nama_prodi' => 'S1 P. PKN', 'singkatan' => 'P. PKN'],
                    ['kode_prodi' => '87203', 'nama_prodi' => 'S1 P. Ekonomi', 'singkatan' => 'PE'],
                    ['kode_prodi' => '84105', 'nama_prodi' => 'S1 P. Biologi', 'singkatan' => 'P.BIO'],
                    ['kode_prodi' => '84202', 'nama_prodi' => 'S1 P. Matematika', 'singkatan' => 'P.MATH'],
                    ['kode_prodi' => '88201', 'nama_prodi' => 'S1 P. B. Indonesia', 'singkatan' => 'PBSI'],
                    ['kode_prodi' => '88203', 'nama_prodi' => 'S1 P. B. Inggris', 'singkatan' => 'P. BING'],
                    ['kode_prodi' => '86207', 'nama_prodi' => 'S1 PGPAUD', 'singkatan' => 'PGPAUD'],
                    ['kode_prodi' => '86206', 'nama_prodi' => 'S1 PGSD', 'singkatan' => 'PGSD'],
                ]
            ],
            [
                'kode_fakultas' => 'FISIP',
                'nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'program_studi' => [
                    ['kode_prodi' => '67201', 'nama_prodi' => 'S1 Ilmu Politik', 'singkatan' => 'IPOL'],
                    ['kode_prodi' => '70201', 'nama_prodi' => 'S1 Ilmu Komunikasi', 'singkatan' => 'ILKOM'],
                ]
            ],
            [
                'kode_fakultas' => 'FT',
                'nama_fakultas' => 'Fakultas Teknik',
                'program_studi'=> [
                    ['kode_prodi' => '26201', 'nama_prodi' => 'S1 Teknik Industri', 'singkatan' => 'TI'],
                    ['kode_prodi' => '55201', 'nama_prodi' => 'S1 Teknik Informatika', 'singkatan' => 'TIF'],
                ]
            ],
            [
                'kode_fakultas' => 'FMIPA',
                'nama_fakultas' => 'Fakultas Matematika dan IPA',
                'program_studi' => [
                    ['kode_prodi' => '44201', 'nama_prodi' => 'S1 Matematika', 'singkatan' => 'MATH'],
                    ['kode_prodi' => '46201', 'nama_prodi' => 'S1 Biologi', 'singkatan' => 'BIO'],
                    
                ]
            ],
            [
                'kode_fakultas' => 'FAKANLUT',
                'nama_fakultas' => 'Fakultas Perikanan dan Kelautan',
                'program_studi' => [
                    ['kode_prodi' => '54242', 'nama_prodi' => 'S1 Ilmu Perikanan', 'singkatan' => 'IPER'],
                    ['kode_prodi' => '54241', 'nama_prodi' => 'S1 Ilmu Kelautan', 'singkatan' => 'IKEL'],
                ]
            ],
            [
                'kode_fakultas' => 'PASCASARJANA',
                'nama_fakultas' => 'Pascasarjana',
                'program_studi' => [
                    ['kode_prodi' => '86122', 'nama_prodi' => 'S2 Pendidikan Dasar', 'singkatan' => 'S2DIKDAS'],
                    ['kode_prodi' => '84205', 'nama_prodi' => 'S2 Pendidikan Biologi', 'singkatan' => 'S2PBIO'],
                ]
            ],
        ];

        foreach ($data as $fakultasData) {
            $fakultas = Fakultas::create([
                'kode_fakultas' => $fakultasData['kode_fakultas'],
                'nama_fakultas' => $fakultasData['nama_fakultas'],
            ]);

            foreach ($fakultasData['program_studi'] as $prodiData) {
                // PERUBAHAN: Menambahkan 'singkatan' saat membuat data prodi
                ProgramStudi::create([
                    'fakultas_id' => $fakultas->id,
                    'kode_prodi' => $prodiData['kode_prodi'],
                    'nama_prodi' => $prodiData['nama_prodi'],
                    'singkatan' => $prodiData['singkatan'] ?? null,
                ]);
            }
        }
    }
}

