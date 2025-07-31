<?php

namespace App\Models;

use App\Models\JawabanIkm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PertanyaanIkm extends Model
{
    use HasFactory;
    protected $fillable = [
        'kd_unsur',
        'pertanyaan',
    ];

    public function jawaban()
    {
        return $this->hasMany(JawabanIkm::class, 'kd_unsur', 'kd_unsur');
    }
}
