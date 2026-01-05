<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenIkmTu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_biodata',
        'kd_unsurikmtu',
        'skor',
    ];

    // Relasi ke tabel Responden
    public function responden()
    {
        return $this->belongsTo(RespondenTu::class, 'id_biodata', 'id');
    }

    public function pertanyaanikmpelayanan()
    {
        return $this->belongsTo(Pertanyaanikmtu::class, 'kd_unsurikmtu', 'kd_unsur');
    }
}
