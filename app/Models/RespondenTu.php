<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenTu extends Model
{
    use HasFactory;
    protected $table = 'respondentus';
    protected $fillable = [
        'nama',
        'usia',
        'gender',
        'nohp',
        'pendidikan',
        'layanan_tu',
        'jabatan',
        'kritik_saran',
    ];

    public function jawabansurvey()
    {
        return $this->hasMany(RespondenIkmTu::class, 'id_biodata', 'id');
    }
}
