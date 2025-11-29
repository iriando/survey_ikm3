<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenIkmPelayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_biodata',
        'kd_unsurikmpelayanan',
        'skor',
    ];

    // Relasi ke tabel Responden
    public function responden()
    {
        return $this->belongsTo(RespondenPelayanan::class, 'id_biodata', 'id');
    }

    public function pertanyaanikmpelayanan()
    {
        return $this->belongsTo(Pertanyaanikmpelayanan::class, 'kd_unsurikmpelayanan', 'kd_unsur');
    }

}
