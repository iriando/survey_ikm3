<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narasumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'kegiatan_id',
        'nama',
        'nip',
        'jabatan',
    ];

    public function respondenikms()
    {
        return $this->hasMany(RespondenIkm::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
