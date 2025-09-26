<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilihan_jawabanikmtu extends Model
{
    use HasFactory;
    protected $fillable = [
        'pertanyaan_id',
        'teks_pilihan',
        'np',
        'mutu',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaanikmtu::class, 'pertanyaan_id');
    }
}
