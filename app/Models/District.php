<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public function regency() {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public function villages () {
        return $this->hasMany(Village::class, 'district_id', 'id');
    }
}
