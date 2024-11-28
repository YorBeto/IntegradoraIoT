<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\Persona;
use Illuminate\Support\Facades\URL;



class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $persona;
    public $activationLink;
    public $imagen;

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
        $this->imagen = URL::to('/').'/images/GamyG1.png'; // URL pública a la imagen

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
                        'activationLink' => $this->activationLink,
                        'imagen' => $this->imagen,
                    ]);

    }
}
