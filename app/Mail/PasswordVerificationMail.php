<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $userName;

    public function __construct($token, $userName = null)
    {
        $this->token = $token;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Password Reset Link - Tay Meng Phone Shop')
                    ->view('emails.password-verification');
    }
} 