<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Auth;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $purchase;
    public $auth;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($purchase, $auth)
    {
        $this->purchase = $purchase;
        $this->auth = $auth;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.index');
    }
}
