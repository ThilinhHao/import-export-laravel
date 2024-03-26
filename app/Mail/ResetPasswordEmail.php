<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;

    /**
     * Create a new message instance.
     *
     * @param  string  $resetUrl
     * @return void
     */
    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $expirationTime = 15; // Thời gian hết hạn link reset mật khẩu (15 phút)

        return $this->view('auth.reset_email')
            ->subject('Reset Password')
            ->with([
                'resetUrl' => URL::temporarySignedRoute(
                    'password.reset',
                    now()->addMinutes($expirationTime),
                    ['token' => $this->resetUrl]
                ),
            ]);
    }
}
