<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kd_kegiatan',
        'n_kegiatan',
        'status',
        'tanggal_kegiatan'
    ];

    public function narasumbers()
    {
        return $this->hasMany(Narasumber::class);
    }

}
