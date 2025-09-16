<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    /** @use HasFactory<\Database\Factories\MobilFactory> */
    use HasFactory;
    protected $guarded = ['id']; 

    public function owner() {
        return $this->belongsTo(Sopir::class); 
    }

    public function kategori() {
        return $this->belongsTo(KategoriMobil::class);
    }

    public function reservasi() {
        return $this->hasMany(Reservasi::class, 'mobil_id');
    }

}
