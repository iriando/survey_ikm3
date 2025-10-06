<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responden extends Model
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
        'kegiatan',
        'j_layanantu',
        'jabatan',
        'kritik_saran',
    ];

    public function jawabansurvey()
    {
        return $this->hasMany(RespondenIkm::class, 'id_biodata', 'id');
    }
}
