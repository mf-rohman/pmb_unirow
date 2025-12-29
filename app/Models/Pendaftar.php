<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'gelombang_id', 'email', 'no_pendaftaran', 'status',
        'jalur_pendaftaran_id', 
        'program_studi_id_1', 
        'program_studi_id_2',
        'nama_lengkap', 'nik', 'nisn', 
        'tempat_lahir', 'tanggal_lahir', 
        'jenis_kelamin', 'agama', 'no_hp',
        'asal_sekolah', 'jurusan_asal_sekolah',
        'nama_ibu_kandung', 'nama_ayah_kandung',
        'alamat_lengkap', 'rt', 'rw',
        'province_id', 'regency_id', 'district_id', 'village_id',
        'nilai_rapor_x_1', 'nilai_rapor_x_2', 
        'nilai_rapor_xi_1', 'nilai_rapor_xi_2',
    ];

    protected $guarded = [];

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jalurPendaftaran () {
        return $this->belongsTo(JalurPendaftaran::class, 'jalur_pendaftaran_id', 'id');
    }

    public function gelombang () {
        return $this->belongsTo(Gelombang::class, 'gelombang_id', 'id');
    }

    public function programStudi1 () {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id_1', 'kode_prodi');
    }

    public function programStudi2 () {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id_2', 'kode_prodi');
    }

    public function province() {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function regency () {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public function district () {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function village () {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }


    public function dokumenPendaftars() {
        return $this->hasMany(DokumenPendaftar::class, 'pendaftar_id', 'id');   
    }
}
