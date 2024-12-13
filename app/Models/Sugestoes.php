<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugestoes extends Model
{
    use HasFactory;

    protected $table = 'sugestoes';

    protected $fillable = [
        'nome',
        'email',
        'conteudo',
        'visible',
        'modificado_por',
        'created_at',
        'updated_at',
    ];
}
