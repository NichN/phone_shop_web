<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerVerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order, $code;

    public function __construct($order, $code)
    {
        $this->order = $order;
        $this->code = $code;
    }

    public function build()
    {
        return $this->view('Admin.mail.verify')
            ->subject('Your Order Verification Code')
            ->with([
                'order' => $this->order,
                'code' => $this->code,
            ]);
    }
}
