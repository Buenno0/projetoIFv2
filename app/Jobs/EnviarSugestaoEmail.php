<?php

namespace App\Jobs;

use App\Mail\SugestaoEnviada;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarSugestaoEmail implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

protected $sugestao;

/**
* Create a new job instance.
*
* @param $sugestao
*/
public function __construct($sugestao)
{
$this->sugestao = $sugestao;
}

/**
* Execute the job.
*
* @return void
*/
public function handle()
{
Mail::to($this->sugestao->email)->send(new SugestaoEnviada($this->sugestao));
}
}
