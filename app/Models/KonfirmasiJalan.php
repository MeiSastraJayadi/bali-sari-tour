<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfirmasiJalan extends Model
{
    /** @use HasFactory<\Database\Factories\KonfirmasiJalanFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
