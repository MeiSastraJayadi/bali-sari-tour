<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\KategoriMobil;
use App\Models\KodeReservasi;
use App\Models\KonfirmasiJalan;
use App\Models\Mobil;
use App\Models\Pelanggan;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class ClientController extends Controller
{
    //
    public function index(Request $request) {

        $kategori = KategoriMobil::all(); 
        $mobil = Mobil::all(); 


        if ($request->has('mobil')) {
            $category = KategoriMobil::where('nama_kategori', $request->query('mobil'))
                ->first(); 
            if ($category) {
                $mobil = Mobil::where('kategori_id', $category->id) 
                    -> get();
            }
        }


        if ($request->has('waktu')) {
            $waktu = $request->query('waktu');
            $tanggalFormatted = Carbon::parse($waktu)->translatedFormat('j F Y');
            $mobil = Mobil::whereDoesntHave('reservasi', function ($query) use ($tanggalFormatted) {
                $query->where('tanggal', $tanggalFormatted);
            })->get();
        }


        if ($request->has('waktu') && $request->has('mobil')) {
            $query = Mobil::query();
            $waktu = $request->query('waktu');
            $tanggalFormatted = Carbon::parse($waktu)->translatedFormat('j F Y');
            $kategoriMobil = $request->query('mobil');
            if ($kategoriMobil) {
                $query->whereHas('kategori', function ($q) use ($kategoriMobil) {
                    $q->where('nama_kategori', $kategoriMobil);
                });
            }
        
            $query->whereDoesntHave('reservasi', function ($q) use ($tanggalFormatted) {
                $q->where('tanggal', $tanggalFormatted);
            });
        
            $mobil = $query->get();
        }

        return view('home', [
            "kategoriMobil" => $kategori, 
            "mobil" => $mobil
        ]); 
    }

    public function reservation(Request $request) {
        
        if (Auth::user() == null) return redirect() -> route('client-login');

        $start = $request->query('date_start');
        $end   = $request->query('date_end');

        $user = Auth::user(); 
        $pelanggan = $user -> pelanggan; 
        $reservasi = $pelanggan -> reservasi; 

        if (!empty($start) && !empty($end)) {
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate   = Carbon::parse($end)->endOfDay();

            $reservasi = Reservasi::where('pelanggan_id', $pelanggan->id) 
                ->get() 
                ->filter(function ($item) use ($startDate, $endDate) { 
                    $tanggal = Carbon::createFromLocaleFormat('d F Y', 'id', $item->tanggal); 
                    return $tanggal->between($startDate, $endDate); });
        }

        $invoices = Invoice::query()
            ->join('kode_reservasis', 'invoices.kode_reservasi', '=', 'kode_reservasis.kode')
            ->join('reservasis', 'kode_reservasis.reservasi_id', '=', 'reservasis.id')
            ->where('reservasis.pelanggan_id', $pelanggan->id)
            ->select('invoices.*')
            ->get();


        return view('reservation', [
            "reservations" => $reservasi, 
            "invoices" => $invoices
        ]); 
    }

    function generateRandomString($length = 10) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $randomString = '';
        $maxIndex = strlen($characters) - 1;
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $maxIndex)];
        }
    
        return $randomString;
    }

    public function register() {
        return view('client-register', [
            "img" => "/images/silas-baisch-rf5R1qXwlDU-unsplash.png",
            "title" => 'Register ke Bali Sari Tour', 
            "googleTitle" => 'Daftar dengan Google', 
            "description" => 'Register untuk mengakses layanan pemesanan driver pribadi, tour eksklusif, dan perjalanan yang nyaman di Bali.'
        ]); 
    }

    public function editProfile(Request $request) {
        $validated = $request->validate([
            'fullname' => ['required', 'string'],
        ]);

        $fullname = $request -> fullname; 
        $address = $request -> address; 
        $phone = $request -> phone; 
        $email = $request -> email; 

        $pelanggan = Pelanggan::where('email', $email) 
            -> update([
                "nama_lengkap" => $fullname, 
                "alamat" => $address, 
                "telepon" => $phone
            ]);
        
        return response([
            "status" => true, 
            "message" => "Berhasil mengubah profil", 
            "data" => $pelanggan
        ]); 
        
    }

    public function login() {
        return view('client-login', [
                "img" => "/images/polina-kuzovkova-LpKmGD4kS1g-unsplash.png",
                "title" => 'Login ke Bali Sari Tour', 
                "googleTitle" => 'Login dengan Google', 
                "description" => 'Masuk ke akun Anda untuk mengakses layanan pemesanan driver pribadi, tour eksklusif, dan perjalanan yang nyaman di Bali.'
        ]); 
    }

    public function loginLogic(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->back(); 
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
        
    }

    public function registerLogic(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);


        Pelanggan::create([
            "nama_lengkap" => $request->name(), 
            "email" => $request -> email(), 
            "user_id" => $user -> id, 
            "alamat" => "", 
            "telepon" => "",
            "profil" => ""
        ]); 

        

        Auth::login($user); 

        return redirect()->back();
    }

    public function booking() {
        
    }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect() -> back(); 
    }

    public function bookingView(Request $request, Mobil $car) 
    {   
        $driver = $car -> owner; 

        Carbon::setLocale('id'); 

        if ($request->has('waktu')) {
            $waktu = $request->query('waktu');
            $time = Carbon::parse($waktu);
        } else {
            $time = Carbon::now();
        }

        $tanggalFormatted = $time->translatedFormat('j F Y');

        
        
        $mobil = Mobil::where('id', $car->id) // spesifik ke mobil tertentu
            ->whereDoesntHave('reservasi', function ($query) use ($tanggalFormatted) {
                $query->where('tanggal', $tanggalFormatted);
            })
            ->first(); 


        return view('booking', [
            "car_id" => $car -> id, 
            "image" => $car -> foto_mobil, 
            "type" => $car -> nama_mobil,
            "name" => $driver -> nama_lengkap, 
            "profilePicture" => $driver -> user -> profile_photo_url, 
            "email" => $driver -> email, 
            "address" => $driver -> alamat, 
            "age" => "", 
            "available" => ($mobil != null), 
            "date" => $time->translatedFormat('d F Y'),
            "defaultDate" => $time->format('Y-m-d')
        ]); 
    }


    public function createReservation(Request $request) 
    {
        $data = $request->all(); 
        $id = (int)$request -> id;
        $user = User::where('email', $request -> user) -> first(); 
        if ($user == null) {
            return response(
                [
                    "status" => false, 
                    "message" => "Pelanggan tidak tersedia", 
                    "data" => null 
                ], 404
            ); 
        }
        $pelanggan = Pelanggan::where('user_id', $user -> id) -> first(); 
        if ($pelanggan == null) {
            return response(
                [
                    "status" => false, 
                    "message" => "Detail pelanggan tidak tersedia", 
                    "data" => null 
                ], 404
            ); 
        }
        $mobil = Mobil::where('id', $id) -> first(); 
        if ($mobil == null) {
            return response(
                [
                    "status" => false, 
                    "message" => "Detail mobil tidak tersedia", 
                    "data" => null 
                ], 404
            ); 
        }
        $name = $request -> name; 
        $address = $request -> address; 
        $destination = $request -> destination; 
        $email = $request -> email; 
        $phone = $request -> phone; 
        $pax = $request -> pax; 
        $note = $request -> note != null ? $request -> note : ''; 
        $date = $request -> time; 

        $reservasi = Reservasi::create([
            "divalidasi" => false,
            "mobil_id" => $mobil -> id, 
            "pelanggan_id" => $pelanggan -> id, 
            "destinasi" => $destination, 
            "alamat" => $address, 
            "biaya" => 0, 
            "nama" => $name, 
            "email" => $email, 
            "telepon" => $phone, 
            "pax" => $pax, 
            "note" => $note, 
            "tanggal" => $date
        ]); 

        $reservationCode = $this -> generateRandomString(); 
        KodeReservasi::create([
            "kode" => $reservationCode, 
            "reservasi_id" => $reservasi->id
        ]); 

        return response([
            "status" => true, 
            "message" => "Data reservasi ditambahkan", 
            "data" => $reservasi
        ]); 
    }
    

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function konfirmasiJalan(Request $request, KodeReservasi $reservasi) {
        $konfirmasi = $reservasi->konfirmasi; 

        if (!$konfirmasi) {
            KonfirmasiJalan::create([
                "kode_reservasi" => $reservasi->kode, 
                "konfirmasi_sopir" => false, 
                "konfirmasi_pelanggan" => true
            ]);
        } else {
            KonfirmasiJalan::where('id', $konfirmasi->id)
                ->update([
                    "konfirmasi_pelanggan" => true
                ]);
        }

        return response([
            "status" => true, 
            "message" => "Berhasil konfirmasi"
        ]); 
    }

    public function handleProviderCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('gauth_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                $secondPrevious = session()->pull('last_url', route('home')); 
                return redirect($secondPrevious);
            }else{
                $plainPassword = Str::random(12); 
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'gauth_id'=> $user->id,
                    'gauth_type'=> 'google',
                    'password' => encrypt($plainPassword)
                ]);

                Pelanggan::create([
                    "nama_lengkap" => $user->name, 
                    "email" => $user -> email, 
                    "user_id" => $newUser -> id, 
                    "alamat" => "", 
                    "telepon" => "",
                    "profil" => ""
                ]); 
        

                Auth::login($newUser);

                $secondPrevious = session()->pull('last_url', route('home')); 
                return redirect($secondPrevious);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
