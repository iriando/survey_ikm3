<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unsurikmpelayanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kd_unsur',
        'nama_unsur',
        'keterangan',

    ];

    public function pertanyaan()
    {
        return $this->hasOne(Pertanyaanikmpelayanan::class, 'kd_unsur', 'kd_unsur');
    }
}
