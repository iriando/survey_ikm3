<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPersepsiIkm extends Model
{
    use HasFactory;
    protected $fillable = [
        'np',
        'ni_terendah',
        'ni_tertinggi',
        'nik_terendah',
        'nik_tertinggi',
        'mutu_pelayanan',
        'kinerja'
    ];
}
