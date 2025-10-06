<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanantu extends Model
{
    use HasFactory;
    protected $fillable = [
        'subbag',
        'j_layanan',
    ];
}
