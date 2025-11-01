<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest_name;
    public $pickup_location;
    public $destination;
    public $price;
    public $note;
    public $confirmation_url;
    public $code; 

    public function __construct($data)
    {
        $this->code = $data['code'];
        $this->guest_name = $data['guest_name'];
        $this->pickup_location = $data['pickup_location'];
        $this->destination = $data['destination'];
        $this->price = $data['price'];
        $this->note = $data['note'];
        $this->confirmation_url = $data['confirmation_url'];
    }

    public function build()
    {
        return $this->subject('Permintaan Reservasi Baru')
                    ->view('emails.konfirmasi');
    }
}
