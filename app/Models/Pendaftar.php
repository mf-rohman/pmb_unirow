<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

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
