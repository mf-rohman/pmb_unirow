<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

        public $incrementing = false;
        protected $keyType = 'string';

        public function regencies() {
            return $this->hasMany(Regency::class, 'province_id', 'id');
        }
}
