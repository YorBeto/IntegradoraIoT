<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $persona;
    public $activationLink;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $activationLink
     */
    public function __construct($persona, $activationLink)
    {
        $this->persona = $persona;
        $this->activationLink = $activationLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Activación de Cuenta')
                    ->view('emails.account_activation');
                    
    }
}
