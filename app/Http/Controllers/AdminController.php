<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\KategoriMobil;
use App\Models\KodeReservasi;
use App\Models\Mobil;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use App\Models\Sopir;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function listPelanggan() {
        return DataTables::of(Pelanggan::query())->make(true); 
    }

    public function pelanggan() {
        return view('admin.pelanggan');
    }

    public function hapusSopir(Sopir $sopir) {
        $id = $sopir->user->id; 
        Mobil::where('owner_id', $sopir->id)->delete();
        Sopir::where('id', $sopir->id)->delete();
        User::where('id', $id)->delete(); 
        return response([
            "status" => true, 
            "message" => "Berhasil menghapus data", 
            "data" => null
        ]);
    }


    public function listSopir() {
        return DataTables::of(Sopir::query())
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function sopirDetail(Sopir $sopir) {
 
        $mobil = Mobil::where('owner_id', $sopir->id)->first(); 

        return response([
            "status" => true, 
            "data" => [
                "sopir" => $sopir, 
                "mobil" => $mobil
            ]
        ], 200); 
    }


    public function updateSopir(Request $request, Sopir $sopir) {
        $validator = Validator::make($request->all(), [
            'name_update'  => 'required|string|max:100',
            'tahun_update' => 'required|digits:4|integer', 
            'telepon_update' => 'required',
            'sopir_update' => 'file|max:2048',      
            'fotoMobil_update' => 'file|max:2048',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); 
        }

        if ($request->hasFile('sopir_update')) {
            $pathSopir = $request->file('sopir_update')->store('uploads', 'public');
            User::where('id', $sopir->user->id)->update([
                "profile_photo_path" => $pathSopir
            ]);
        }


        $data = [
            "nama_lengkap" => $request->name_update, 
            "alamat" => $request->alamat_update, 
            "telepon" => $request->telepon_update, 
        ];

        Sopir::where('id', $sopir->id)->update($data); 

        $mobil = Mobil::where('owner_id', $sopir->id)->first();
        $dataMobil = [
            "nama_mobil" => $request->mobil_update, 
            "tahun" => $request->tahun_update, 
            "kategori_id" => $request->kategori_update
        ];
        if ($request->hasFile('fotoMobil_update')) {
            $pathMobil = $request->file('fotoMobil_update')
                ->store('uploads', 'public');
            $dataMobil["foto_mobil"] = $pathMobil; 
        }
        Mobil::where('id', $mobil->id)->update($dataMobil);
        return response([
            "status" => true, 
            "message" => "Berhasil merubah data"
        ], 200);
    }



    public function tambahSopir(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:sopirs,email',
            'telepon' => 'required',
            'tahun' => 'required|digits:4|integer', 
            'sopir' => 'required|file|max:2048',      
            'fotoMobil' => 'required|file|max:2048',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); 
        }

        $pathSopir = '';
        $pathMobil = '';
        if ($request->hasFile('sopir')) {
            $pathSopir = $request->file('sopir')->store('uploads', 'public');
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Foto sopir tidak tersedia'
            ], 422); 
        }

        if ($request->hasFile('fotoMobil')) {
            $pathMobil = $request->file('fotoMobil')->store('uploads', 'public');
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => 'Foto mobil tidak tersedia'
            ], 422); 
        }



        $user = User::create([
            "name" => $request->name, 
            "email" => $request->email, 
            "password" => Hash::make($request->email.'123'), 
            "profile_photo_path" => $pathSopir
        ]);

        $sopir = Sopir::create([
            "nama_lengkap" => $request->name, 
            "profil" => '', 
            "alamat" => $request->alamat, 
            "email" => $request->email, 
            "telepon" => $request->telepon, 
            "user_id" => $user->id
        ]);

        $mobil = Mobil::create([
            "nama_mobil" => $request->mobil, 
            "tahun" => $request->tahun, 
            "foto_mobil" => $pathMobil, 
            "owner_id" => $sopir->id, 
            "kategori_id" => $request->kategori
        ]);


        return response([
            "status" => true, 
            "message" => "Berhasil menambah data", 
            "data" => $sopir
        ], 200); 
    }

    public function sopir() {
        $kategori = KategoriMobil::all(); 
        return view('admin.sopir', ["kategori" => $kategori]);
    }

    public function jadwal() {
        $mobil = Mobil::all();
        return view('admin.jadwal', [
            "mobil" => $mobil
        ]);
    }

    public function hapusJadwal(Reservasi $reservasi) {
        KodeReservasi::where('reservasi_id', $reservasi->id)->delete();
        Reservasi::where('id', $reservasi->id)->delete(); 
        return response([
            "status" => true, 
            "message" => "Berhasil menghapus data reservasi"
        ], 200);
    }

    public function listJadwal() {
        $data = Reservasi::with(['pelanggan', 'mobil']);
        return DataTables::of($data)
        ->addColumn('pelanggan', function ($row) {
            $col = $row->pelanggan ? $row->pelanggan->nama_lengkap : '-';
            $konfirmasiJalan = $row -> kode_reservasi->konfirmasi; 
            return $col; 
        })
        ->addColumn('sopir', function ($row) {
            $col = $row->mobil ? $row->mobil->owner->nama_lengkap : '-';
            return $col; 
        })
        ->addColumn('action', function ($row) {
            $col = '
                <button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'" title="Edit">
                    <i class="fas fa-pencil-alt"></i> Detail
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" title="Delete">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            ';
            if ($row->divalidasi) {
                $col = '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$row->id.'" title="Edit">
                            <i class="fas fa-pencil-alt"></i> Detail
                        </button>';
            }
            return $col;
        })
        ->rawColumns(['pelanggan', 'sopir', 'action'])
        ->make(true);
    }

    public function detailJadwal(Reservasi $jadwal) {
        return response([
            "status" => true, 
            "data" => $jadwal
        ], 200); 
    }

    public function updateJadwal(Request $request, Reservasi $jadwal) {
        $data = [
            "destinasi" => $request->destinasi_update, 
            "biaya" => $request->fee_update, 
            "pax" => $request->pax_update, 
            "mobil_id" => $request->mobil 
        ];

        Reservasi::where('id', $jadwal->id)->update($data); 

        return response([
            "status" => true, 
            "message" => "Berhasil mengubah data jadwal"
        ], 200);
    }

    public function invoiceJadwalList() {
        $data = Reservasi::with(['pelanggan', 'mobil', 'kode_reservasi']);
        return DataTables::of($data)
            ->addColumn('pelanggan', function ($row) {
                $col = $row->pelanggan ? $row->pelanggan->nama_lengkap : '-';
                $konfirmasiJalan = $row -> kode_reservasi->konfirmasi; 
                if ($konfirmasiJalan) {
                    if ($konfirmasiJalan -> konfirmasi_pelanggan) {
                        $col .= '<span class="badge ml-3 bg-success">Terkonfirmasi</span>';
                    }
                }
                return $col; 
            })
            ->addColumn('sopir', function ($row) {
                $col = $row->mobil ? $row->mobil->owner->nama_lengkap : '-';
                $konfirmasiJalan = $row -> kode_reservasi->konfirmasi; 
                if ($konfirmasiJalan) {
                    if ($konfirmasiJalan -> konfirmasi_pelanggan) {
                        $col .= '<span class="badge ml-3 bg-success">Terkonfirmasi</span>';
                    }
                }
                return $col; 
            })
            ->addColumn('action', function ($row) {
                if (!$row->kode_reservasi->invoice) {
                    $col = '<button class="btn btn-sm btn-success generate-btn" data-id="'.$row->kode_reservasi->kode.'" title="Edit">
                                <i class="fas fa-pencil-alt"></i> Generate Invoice
                            </button>';
                } else {
                    $col = '
                    <a href="'.route('admin.invoice.print-invoice', ['reservasi' => $row->kode_reservasi->kode]).'" class="btn btn-sm btn-primary detail-btn" data-id="'.$row->kode_reservasi->kode.'" title="Edit">
                        <i class="fas fa-eye"></i> Lihat Invoice
                    </a>
                    ';
                }
                return $col;
            })
            ->rawColumns(['pelanggan', 'sopir', 'action'])
            ->make(true);
    }

    public function detailJadwalInvoice(KodeReservasi $kodeReservasi) {
        $reservasi = $kodeReservasi->reservasi; 
        return response([
            "status" => true, 
            "data" => [
                "reservasi" => $reservasi, 
                "kode_reservasi" => $kodeReservasi
            ]
        ], 200);
    }

    public function invoiceToPdf(KodeReservasi $reservasi) {
        $data = [
            'invoice_number' => $reservasi->kode,
            'date' => $reservasi->reservasi->created_at->format('d/m/Y'),
            'company' => [
                'name' => 'Bali Sari Tour',
            ],
            'customer' => [
                'name' => $reservasi->reservasi->nama,
                'phone' => $reservasi->reservasi->telepon
            ],
            'trip' => [
                'driver' => $reservasi->reservasi->mobil->owner->nama_lengkap,
                'pickup' => $reservasi->reservasi->alamat,
                'destination' => $reservasi->reservasi->destinasi,
                'date' => $reservasi->reservasi->tanggal,
                'price' => $reservasi->reservasi->biaya
            ]
        ];


        $pdf = Pdf::loadView('admin.pdf.invoice', $data);
        return $pdf->download('invoice-transport-'.$reservasi->kode.'.pdf');
    }


    public function generateInvoice(Request $request, KodeReservasi $kodeReservasi) {
        $data = [
            "kode_reservasi" => $kodeReservasi->kode, 
            "lunas" => false, 
            "tanggal_bayar" => Carbon::now(), 
            "biaya" => $request->fee_update, 
            "metode" => ""
        ]; 

        $invoice = Invoice::create($data);
        
        return response([
            "status" => true, 
            "message" => "Berhasil membuat invoice"
        ], 200);
    }

    public function showLogin() {
        return view('auth.auth-admin', [
            "title" => "Selamat Datang di Halaman Admin", 
            "description" => "Masuk ke akun Anda untuk mengelola data pelanggan, jadwal, dan pembayaran pada sistem.", 
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

            return redirect()->route('admin.dashboard'); 
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect() -> route('admin.auth.view');
    }

    public function invoice() {
        return view('admin.invoice');
    }

}
