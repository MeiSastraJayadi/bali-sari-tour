<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservasi extends Model
{
    /** @use HasFactory<\Database\Factories\ReservasiFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id']; 

    public function mobil() {
        return $this -> belongsTo(Mobil::class, "mobil_id"); 
    }

    public function pelanggan() {
        return $this -> belongsTo(Pelanggan::class);
    }

    public function kode_reservasi() {
        return $this -> hasOne(KodeReservasi::class, "reservasi_id"); 
    }
}
