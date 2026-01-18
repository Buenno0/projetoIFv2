<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

class Sugestao extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = 'sugestoes';

    protected $fillable = [
        'conteudo',
        'nome',
        'email',
        'respondido',        // tinyint
        'data_resposta',     // datetime
        'id_user_responded', // bigint
        'respondido_por',    // varchar
        'id_user_deleted',   // bigint
        'modificado_por',    // varchar
        'conteudo_resposta'  // text
    ];

    protected $casts = [
        'respondido' => 'boolean',
        'data_resposta' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($sugestao) {
            if (Auth::check()) {
                $sugestao->id_user_deleted = Auth::id();
                $sugestao->saveQuietly();
            }
        });
    }

    /* * CORREÇÃO DO ERRO: 
     * Adicionamos de volta o escopo, mesmo que vazio, para não quebrar o Controller.
     */
    public function scopeVisiveis($query)
    {
        return $query; // O SoftDeletes já filtra automaticamente
    }

    public function scopeNaoRespondidas($query)
    {
        // Baseado no seu banco (tinyint 0 ou 1)
        return $query->where('respondido', false); 
    }
}