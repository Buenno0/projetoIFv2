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
        'respondido',
        'data_resposta',
        'id_user_responded',
        'respondido_por',
        'deleted_at',
        'id_user_deleted',
        'visible',
        'modificado_por',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'respondido' => 'boolean',
        'visible'    => 'boolean',
        'data_resposta' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
