<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanIkm extends Model
{
    use HasFactory;
    protected $fillable = [
        'kd_unsur',
        'isi_jawaban',
        'np',
        'mutu',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanIkm::class, 'kd_unsur', 'kd_unsur');
    }
}
