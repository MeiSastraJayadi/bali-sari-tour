<?php

namespace App\Http\Controllers;

use App\Models\KodeReservasi;
use App\Models\KonfirmasiJalan;
use App\Models\Mobil;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NumberFormatter;
use Yajra\DataTables\Facades\DataTables;

class SopirController extends Controller
{
    public function jadwal() {
        return view('sopir.jadwal');
    }

    public function jadwalData(Request $request) {
        $sopirId = $request->user()->sopir->id; 
        $mobil = Mobil::where('owner_id', $sopirId)->first(); 
        $data = Reservasi::where('mobil_id', $mobil->id) -> where('biaya', '!=', 0);
        return DataTables::of($data)
            ->addColumn('pelanggan', function ($row) {
                return $row->pelanggan ? $row->pelanggan->nama_lengkap : '-';
            })
            ->addColumn('sopir', function ($row) {
                return $row->mobil ? $row->mobil->owner->nama_lengkap : '-';
            })
            ->addColumn('action', function ($row) {
                $col = '
                    <button class="btn btn-sm btn-success update-btn" data-id="'.$row->kode_reservasi->kode.'" title="Konfirmasi">
                        <i class="fas fa-check"></i> Konfirmasi Jalan
                    </button>
                ';
                if ($row->kode_reservasi->konfirmasi != null) {
                    if ($row->kode_reservasi->konfirmasi->konfirmasi_sopir) {
                        $col = '';
                    }
                }
                return $col;
            })
            ->make(true);
    }

    public function fee() {
        return view('sopir.fee'); 
    }
    
    function formatRupiah($amount) {
        return "Rp " . number_format($amount, 0, ',', '.');
    }

    public function konfirmasiJalan(Request $request, KodeReservasi $reservasi) {
        $konfirmasi = $reservasi->konfirmasi; 

        if (!$konfirmasi) {
            KonfirmasiJalan::create([
                "kode_reservasi" => $reservasi->kode, 
                "konfirmasi_sopir" => true, 
                "konfirmasi_pelanggan" => false
            ]);
        } else {
            KonfirmasiJalan::where('id', $konfirmasi->id)
                ->update([
                    "konfirmasi_sopir" => true
                ]);
        }

        return response([
            "status" => true, 
            "message" => "Berhasil konfirmasi"
        ]); 
    }

    public function feeData(Request $request) {
        $sopirId = $request->user()->sopir->id; 
        $mobil = Mobil::where('owner_id', $sopirId)->first(); 
        $data = Reservasi::where('mobil_id', $mobil->id)->where('biaya', '!=', 0);
        return DataTables::of($data)
            ->addColumn('pelanggan', function ($row) {
                return $row->pelanggan ? $row->pelanggan->nama_lengkap : '-';
            })
            ->addColumn('biaya', function ($row) {
                return $this->formatRupiah($row->biaya);
            })
            ->make(true);
    }

    public function loginView() {
        return view('auth.auth-sopir', [
            "title" => "Selamat Datang di Halaman Sopir", 
            "description" => "Masuk ke akun Anda untuk melihat jadwal keberangkatan pelanggan", 
            "formTitle" => "Login ke Bali Sari Tour"
        ]);
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('sopir.jadwal.view'); 
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect() -> route('sopir.login.view'); 
    }
}
