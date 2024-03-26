<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificationLink)
    {


        $this->verificationLink = $verificationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verificationLink = str_replace(url('/email/verify/'), '', $this->verificationLink);
        $verificationLink = trim($verificationLink, '/');
        $expirationTime = 15;
        // tạo đường dẫn tạm thời
        $temporaryUrl = URL::temporarySignedRoute(
            'email.verify',
            now()->addMinutes($expirationTime),
            ['token' =>  $verificationLink]
        );

        return $this->view('auth.verification_email')->subject('Verify Your Email')->with('temporaryUrl', $temporaryUrl);

    }
}
