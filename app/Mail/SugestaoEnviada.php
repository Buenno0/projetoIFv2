<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SugestaoEnviada extends Mailable
{
    use Queueable, SerializesModels;

    public $sugestao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sugestao)
    {
        $this->sugestao = $sugestao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sugestão Recebida')
            ->view('emails.thanks_email');
    }
}
