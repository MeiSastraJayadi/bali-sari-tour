<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\KodeReservasi;
use App\Models\MidtransTransaction;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request) {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$is3ds = true; 
        Config::$isSanitized = true; 

        $reservasi = KodeReservasi::where('kode', $request -> kode_reservasi)
            ->first();
        $invoice = $reservasi -> invoice;
        $detailReservasi = $reservasi->reservasi;

        $params = [
            "transaction_details" => [
                "order_id" => $request -> kode_reservasi, 
                "gross_amount" => $invoice->biaya
            ], 
            'customer_details' => [
                'first_name' => $detailReservasi->nama, 
                'email' => $detailReservasi->email, 
                'phone' => $detailReservasi->telepon
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }

    public function paymentSuccess(Request $request) {
        $validated = $request->validate([
            'order_id' => ['required', 'string'],
            'gross_amount' => ['required'], 
            'transaction_id' => ['required'], 
            'transaction_time' => ['required'], 
            'transaction_status' => ['required']
        ]);

        $kodeReservasi = KodeReservasi::where('kode', $request->order_id) -> first(); 
        $invoice = $kodeReservasi->invoice;
        $reservasi = $kodeReservasi->reservasi; 

        Invoice::where('id', $invoice -> id)->update([
            'lunas' => true, 
            'tanggal_bayar' => Carbon::now()
        ]);

        Reservasi::where('id', $reservasi->id)->update([
            'divalidasi' => true
        ]); 

        $data = [
            'order_id' => $request->order_id, 
            'signature_key' => '', 
            'gross_ammounts' => $request->gross_amount, 
            'transaction_id' => $request->transaction_id, 
            'transaction_time' => $request->transaction_time, 
            'transaction_status' => $request->transaction_status
        ];
        
        MidtransTransaction::create($data); 

        return response([
            'status' => true, 
            'message' => "Sukses melakukan pembayaran"
        ], 200);
    }


    public function cashPayment(Request $request) {
        $validated = $request -> validate([
            "kode_reservasi" => "required"
        ]); 
     
        $kodeReservasi = KodeReservasi::where('kode', $request->kode_reservasi) -> first(); 
        // $invoice = $kodeReservasi->invoice;
        $reservasi = $kodeReservasi->reservasi;
        

        Reservasi::where('id', $reservasi->id)->update([
            'divalidasi' => true
        ]); 

        return response([
            'status' => true, 
            'message' => "Sukses konfirmasi pembayaran cash"
        ], 200);

    }

}
