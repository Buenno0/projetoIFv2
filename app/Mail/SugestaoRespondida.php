<?php

namespace App\Mail;

use App\Models\Sugestao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SugestaoRespondida extends Mailable
{
    use Queueable, SerializesModels;

    public $sugestao;

    /**
     * Recebe a sugestão para podermos usar os dados na view.
     */
    public function __construct(Sugestao $sugestao)
    {
        $this->sugestao = $sugestao;
    }

    /**
     * Define a view e o assunto do e-mail.
     */
    public function build()
    {
        return $this
            ->subject('Sua sugestão no ConectaIF foi respondida!') // Assunto
            ->view('emails.sugestao-respondida'); // Nome do arquivo Blade (vamos criar abaixo)
    }
}