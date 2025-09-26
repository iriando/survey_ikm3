<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondenIkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_biodata',
        'kd_unsurikmpelayanan',
        'kd_unsurikmpembinaan',
        'kd_unsurikmtu',
        'narasumber_id',
        'skor',
    ];

    // Relasi ke tabel Responden
    public function responden()
    {
        return $this->belongsTo(Responden::class, 'id_biodata', 'id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'kd_unsurikmpembinaan', 'kd_unsur');
    }

    public function pertanyaanikmpelayanan()
    {
        return $this->belongsTo(Pertanyaanikmpelayanan::class, 'kd_unsurikmpelayanan', 'kd_unsur');
    }

    public function pertanyaanikmtu()
    {
        return $this->belongsTo(Pertanyaanikmtu::class, 'kd_unsurikmtu', 'kd_unsur');
    }

    public function narasumber()
    {
        return $this->belongsTo(Narasumber::class);
    }
}
