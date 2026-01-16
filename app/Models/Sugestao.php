<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Sugestao extends Model
{
    use HasFactory;

    protected $table = 'sugestoes';

    // Campos preenchíveis via mass assignment
    protected $fillable = [
        'nome',
        'email',
        'conteudo',
        'respondido',
        'data_resposta',
        'id_user_responded',
        'respondido_por', // se for relacionamento, use 'respondido_por_id' e defina o relacionamento no modelo
        // 'respondido_por', // se for string/nome direto e precisar preencher, mantenha
        'id_user_deleted',
        'visible',
        'modificado_por',
        // NÃO incluir created_at/updated_at/deleted_at aqui
    ];

    // Casts de tipos
    protected $casts = [
        'respondido'    => 'boolean',
        'visible'       => 'boolean',
        'data_resposta' => 'datetime',
        'deleted_at'    => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    // Escopo local para filtrar visíveis
    public function scopeVisiveis(Builder $query): Builder
    {
        return $query->where('visible', true);
    }

    public function scopeNaoRespondidas($q)
    {
        return $q->where(function($q){
        $q->where('respondido', false)->orWhereNull('respondido');
    });
}

    

   
}
