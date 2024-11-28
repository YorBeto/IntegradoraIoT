<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Persona;
use App\Models\User;

class NotifyAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $persona;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Persona $persona,User $user)
    {
        $this->persona = $persona;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Notify Admin',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        return $this->subject('Notificacion de nuevo usuario')
                    ->view('emails.notifyAdmin') 
                    ->with([
                        'nombre' => $this->persona->nombre,
                        'apellido' => $this->persona->apellido,
                        'email' => $this->user->email,
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
}
