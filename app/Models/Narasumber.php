<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narasumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
    ];

    public function respondenikms()
    {
        return $this->hasMany(RespondenIkm::class);
    }
}
