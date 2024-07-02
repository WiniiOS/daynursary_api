<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCenter extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationToken;

    public function __construct($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    public function build()
    {
        return $this->subject('Verify Your Center')
                    ->view('emails.verify-center')
                    ->with([
                        'verificationToken' => $this->verificationToken,
                    ]);
    }
}
