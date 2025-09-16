<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SopirController;
use App\Http\Middleware\CheckAdminMiddleware;
use App\Http\Middleware\CheckSopirMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClientController::class, 'index']) -> name('home');
Route::get('/reservation', [ClientController::class, 'reservation']) -> name('reservation'); 
Route::post('/edit-profile', [ClientController::class, 'editProfile']) -> name('edit-profile');

Route::group(['prefix' => '/login'], function() {
    Route::get('/', [ClientController::class, 'login']) -> name('client-login'); 
    Route::post('/', [ClientController::class, 'loginLogic']) -> name('client-login-logic'); 
}); 

Route::group(['prefix' => '/register'], function() {
    Route::get('/', [ClientController::class, 'register']) -> name('client-register'); 
    Route::post('/', [ClientController::class, 'registerLogic']) -> name('client-register-logic'); 
}); 

Route::group(['prefix' => '/book'], function() {
    Route::get('/{car:id}', [ClientController::class, 'bookingView']) -> name('booking-view'); 
    Route::post('/create-reservation', [ClientController::class, 'createReservation']) -> name('create-reservation');
    Route::post('/payment-token', [PaymentController::class, 'getSnapToken'])->name('snap-token'); 
    Route::post('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment-success');
    Route::get('/konfirmasi/{reservasi:kode}', [ClientController::class, 'konfirmasiJalan'])->name('konfirmasi-client');
}); 


Route::get('/logout', [ClientController::class, 'logout']) -> name('client-logout'); 

Route::get('oauth/google', [ClientController::class, 'redirectToProvider'])->name('oauth.google');  
Route::get('oauth/google/callback', [ClientController::class, 'handleProviderCallback'])->name('oauth.google.callback');


Route::group(['prefix' => '/admin', "as" => "admin."], function() {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard')->middleware(CheckAdminMiddleware::class);
    
    Route::group(["prefix" => '/auth', "as" => "auth."], function() {
        Route::get("/login", [AdminController::class, 'showLogin'])->name('view');
        Route::post("/login", [AdminController::class, 'login'])->name('login');
        Route::get("/logout", [AdminController::class, 'logout'])->name('logout');
    });
    
    Route::group(["prefix" => "/pelanggan", "as" => "pelanggan.", 'middleware' => [CheckAdminMiddleware::class]], function() {
        Route::get('/', [AdminController::class, 'pelanggan'])->name('view');
        Route::get('/data', [AdminController::class, 'listPelanggan'])->name('data');
    });

    Route::group(["prefix" => "/sopir", "as" => "sopir.", 'middleware' => [CheckAdminMiddleware::class]], function() {
       Route::get('/', [AdminController::class, "sopir"])->name('view');
       Route::get('/data', [AdminController::class, 'listSopir'])->name('data');
       Route::post('/tambah', [AdminController::class, 'tambahSopir'])->name('tambah');
       Route::get('/hapus/{sopir:id}', [AdminController::class, 'hapusSopir'])->name('hapus'); 
       Route::get('/detail/{sopir:id}', [AdminController::class, 'sopirDetail'])->name('detail'); 
       Route::post('/update/{sopir:id}', [AdminController::class, 'updateSopir'])->name('update'); 
    });

    Route::group(['prefix' => '/jadwal', 'as' => 'jadwal.', 'middleware' => [CheckAdminMiddleware::class]], function() {
        Route::get('/', [AdminController::class, 'jadwal'])->name('view');
        Route::get('/data', [AdminController::class, 'listJadwal'])->name('data');
        Route::get('/hapus/{reservasi:id}', [AdminController::class, 'hapusJadwal'])->name('hapus');
        Route::get('/detail/{jadwal:id}', [AdminController::class, 'detailJadwal'])->name('detail'); 
        Route::post('/update/{jadwal:id}', [AdminController::class, 'updateJadwal'])->name('update'); 
    });

    Route::group(['prefix' => '/invoice', 'as' => 'invoice.', 'middleware' => [CheckAdminMiddleware::class]], function() {
        Route::get('/', [AdminController::class, 'invoice'])->name('view');
        Route::get('/data', [AdminController::class, 'invoiceJadwalList'])->name('data');
        Route::get('/detail/{kodeReservasi:kode}', [AdminController::class, 'detailJadwalInvoice'])->name('detail');
        Route::post("/generate-invoice/{kodeReservasi:kode}", [AdminController::class, 'generateInvoice'])->name('generate-invoice');
        Route::get('/print-invoice/{reservasi:kode}', [AdminController::class, 'invoiceToPdf'])->name('print-invoice');
    }); 
});

Route::group(['prefix' => '/auth', 'as' => 'sopir.login.'], function() {
    Route::get('/login', [SopirController::class, 'loginView'])->name('view');
    Route::post('/login', [SopirController::class, 'login'])->name('login');
    Route::get('/logout', [SopirController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => '/sopir', 'as' => 'sopir.', 'middleware' => [CheckSopirMiddleware::class]], function() {

    Route::group(['prefix' => '', 'as' => 'jadwal.', 'middleware' => [CheckSopirMiddleware::class]], function() {
        Route::get('/', [SopirController::class, 'jadwal'])->name('view');
        Route::get('/data', [SopirController::class, 'jadwalData'])->name('data');
        Route::get('/konfirmasi/{reservasi:kode}', [SopirController::class, 'konfirmasiJalan'])->name('konfirmasi');
    });
    
    Route::group(['prefix' => '/fee', 'as' => 'fee.', 'middleware' => [CheckSopirMiddleware::class]], function() {
        Route::get('/', [SopirController::class, 'fee'])->name('view');
        Route::get('/data', [SopirController::class, 'feeData'])->name('data');
    });
});


