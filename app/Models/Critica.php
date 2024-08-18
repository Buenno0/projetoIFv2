<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critica extends Model
{
    use HasFactory;

    protected $table = 'critica';

    protected $fillable = [
        'nome',
        'critica',
        'conteudo',
        'visible',
        'modificado_por',
        'created_at',
        'ultima_modificacao',
    ];
}
