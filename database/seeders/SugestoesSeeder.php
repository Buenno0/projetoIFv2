<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sugestao;

class SugestoesSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 50 sugestões variadas
        Sugestao::factory(30)->create(); // 30 normais
        Sugestao::factory(10)->respondida()->create(); // 10 respondidas
        Sugestao::factory(10)->deletada()->create(); // 10 deletadas
    }
}