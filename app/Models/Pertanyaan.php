<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'unsur_id',
        'teks_pertanyaan',
    ];

    public function unsur()
    {
        return $this->belongsTo(Unsur::class);
    }

    public function pilihanJawabans()
    {
        return $this->hasMany(Pilihan_jawaban::class);
    }

}
