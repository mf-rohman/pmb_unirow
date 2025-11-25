<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurPendaftaran extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pendaftars() {
        return $this->hasMany(Pendaftar::class, 'jalu_pendaftaran_id', 'id');
    }
}
