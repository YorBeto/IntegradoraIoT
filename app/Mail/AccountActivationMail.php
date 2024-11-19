<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\Persona;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $persona;
    public $activationLink;

    /**
     * Create a new message instance.
     *
     * @param Persona $persona
     * @param string $activationLink
     */
    public function __construct(Persona $persona, $activationLink)
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
                    ->view('emails.account_activation', [
                        'persona' => $this->persona,
                        'activationLink' => $this->activationLink
                    ]);
    }
}
