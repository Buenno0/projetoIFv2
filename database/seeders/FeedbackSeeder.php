<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        DB::table('feedback')->insert([
            [
                'nome' => 'João Silva',
                'feedback' => 'Excelente',
                'conteudo' => 'O serviço foi ótimo, recomendo a todos!',
                'visible' => true,
                'modificado_por' => 'Administrador',
            ],
            [
                'nome' => 'Maria Souza',
                'feedback' => 'Bom',
                'conteudo' => 'Gostei do atendimento, mas poderia melhorar em alguns pontos.',
                'visible' => true,
                'modificado_por' => 'Administrador',
            ],
        ]);
    }
}
