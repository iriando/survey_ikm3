<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaanikmtu extends Model
{
    use HasFactory;
    protected $fillable = [
        'unsur_id',
        'teks_pertanyaan',
    ];

    public function unsur()
    {
        return $this->belongsTo(Unsurikmtu::class, 'unsur_id');
    }

    public function pilihanJawabans()
    {
        return $this->hasMany(Pilihan_jawabanikmtu::class, 'pertanyaan_id');
    }
}
