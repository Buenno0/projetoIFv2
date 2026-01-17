<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // Importante para pegar IDs reais se precisar

class SugestaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            // realText gera frases mais coerentes que o lorem ipsum comum
            'conteudo' => fake()->realText(200),
            
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            
            // Gera datas aleatórias de 1 ano atrás até agora
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Estado para sugestões já respondidas
     */
    public function respondida()
    {
        return $this->state(function (array $attributes) {
            return [
                'respondida_em' => fake()->dateTimeBetween($attributes['created_at'], 'now'),
                'resposta' => fake()->realText(100), // Caso você tenha esse campo
            ];
        });
    }

    /**
     * Estado para sugestões DELETADAS (Soft Delete)
     * Isso vai simular itens na lixeira
     */
    public function deletada()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => fake()->dateTimeBetween($attributes['created_at'], 'now'),
                // Vamos pegar um ID de usuário aleatório ou criar um se não existir
                'id_user_deleted' => User::inRandomOrder()->first()?->id ?? User::factory(),
            ];
        });
    }
}