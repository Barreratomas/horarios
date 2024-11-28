<?php

namespace App\Mail\horarios;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Env;

class AssignedToSchedule extends Mailable
{
    use Queueable, SerializesModels;

    
    public function __construct(public $nombre)
    {
    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Asignado a horario',
            from: new Address(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
           
        );
    }

   
    public function content(): Content
    {
        return new Content(
            view: 'mail.assigned_to_schedule', 
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
