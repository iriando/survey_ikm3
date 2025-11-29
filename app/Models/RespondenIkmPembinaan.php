<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenIkmPembinaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_biodata',
        'kd_unsurikmpembinaan',
        'skor',
    ];

    // Relasi ke tabel Responden
    public function responden()
    {
        return $this->belongsTo(RespondenPembinaan::class, 'id_biodata', 'id');
    }

    public function pertanyaanikmpembinaan()
    {
        return $this->belongsTo(pertanyaanikmpembinaan::class, 'kd_unsurikmpelayanan', 'kd_unsur');
    }
}
