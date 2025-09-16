<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KodeReservasi extends Model
{
    /** @use HasFactory<\Database\Factories\KodeReservasiFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ["id"];

    public function invoice() {
        return $this->hasOne(Invoice::class, "kode_reservasi", "kode");
    }

    public function konfirmasi() {
        return $this->hasOne(KonfirmasiJalan::class, "kode_reservasi", "kode");
    }

    public function reservasi() {
        return $this->belongsTo(Reservasi::class);
    }
}
