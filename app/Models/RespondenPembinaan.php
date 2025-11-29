<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenPembinaan extends Model
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
        'kegiatan',
        'jabatan',
        'kritik_saran',
    ];

    public function jawabansurvey()
    {
        return $this->hasMany(RespondenIkmPeembinaan::class, 'id_biodata', 'id');
    }
}
