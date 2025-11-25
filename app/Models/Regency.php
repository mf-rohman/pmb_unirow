<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public function province () {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function districts() {
        return $this->hasMany(District::class, 'regency_id', 'id');
    }
}
