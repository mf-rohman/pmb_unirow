<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'kode_prodi';

    protected $fillable = [
        'fakultas_id',
        'kode_prodi',
        'nama_prodi',
        'singkatan',
    ];

    
    public function fakultas() {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function pilihanPertamaPendaftar() {
        return $this->hasMany(Pendaftar::class, 'program_studi_id_1', 'kode_prodi');
    }
    public function pilihanKeduaPendaftar() {
        return $this->hasMany(Pendaftar::class, 'program_studi_id_2', 'kode_prodi');
    }
}
