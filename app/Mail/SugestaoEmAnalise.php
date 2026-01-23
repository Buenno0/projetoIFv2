<?php

namespace App\Mail;

use App\Models\Sugestao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // <--- IMPORTANTE: Adicione isso
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// Adicione "implements ShouldQueue" na classe
class SugestaoEmAnalise extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sugestao;

    public function __construct(Sugestao $sugestao)
    {
        $this->sugestao = $sugestao;
    }

    public function build()
    {
        return $this->subject('ConectaIF - Sua sugestão está em análise')
                    ->view('emails.sugestao_em_analise');
    }
}