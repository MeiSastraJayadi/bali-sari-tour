<?php

use App\Http\Controllers\API\CreateReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => '/reservation', 
'middleware' => ['auth:sanctum']], function() {
    Route::post('/create-reservation', CreateReservationController::class) -> name('create-reservation-api'); 
}); 