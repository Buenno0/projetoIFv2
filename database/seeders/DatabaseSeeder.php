<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Adicione esta linha se o erro persistir reclamando do SugestoesSeeder
use Database\Seeders\SugestoesSeeder; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SugestoesSeeder::class);
    }
}