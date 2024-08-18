<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critica extends Model
{
    use HasFactory;

    protected $table = 'criticas';

    protected $fillable = [
        'conteudo',
        'visible',
        'modificado_por',
        'created_at',
        'ultima_modificacao',
    ];

    // Indica que não deve usar os timestamps padrão do Laravel
    public $timestamps = false;

    // Configura o campo 'created_at' para ser tratado como uma instância de Carbon
    protected $casts = [
        'created_at' => 'datetime',
        'ultima_modificacao' => 'datetime', // Caso 'ultima_modificacao' também seja uma data
    ];
}