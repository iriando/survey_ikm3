<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenPelayanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'usia',
        'gender',
        'nohp',
        'pendidikan',
        'pekerjaan',
        'instansi',
        'j_layanan',
        'jabatan',
        'kritik_saran',
    ];

    public function jawabansurvey()
    {
        return $this->hasMany(RespondenIkmPelayanan::class, 'id_biodata', 'id');
    }
}
