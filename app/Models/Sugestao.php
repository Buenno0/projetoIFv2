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
    use SoftDeletes; // O Laravel gerencia o 'deleted_at' automaticamente
    use HasFactory;

    protected $table = 'sugestoes';

    protected $fillable = [
        'conteudo',
        'nome',
        'email',
        'id_user_deleted'
    ];

    /**
     * O 'booted' é o lugar perfeito para interceptar o delete.
     */
    protected static function booted()
    {
        static::deleting(function ($sugestao) {
            // Antes de deletar, salvamos o ID do usuário logado
            if (Auth::check()) {
                $sugestao->id_user_deleted = Auth::id();
                
                // saveQuietly: Salva o ID no banco SEM disparar eventos.
                // Isso evita que o Auditor crie um log de "UPDATED" desnecessário.
                $sugestao->saveQuietly();
            }
        });
    }

    // --- ESCOPOS (Ficaram mais limpos) ---

    // O escopo 'visiveis' agora é redundante, pois o SoftDeletes
    // já esconde os deletados por padrão. Mas se quiser manter o nome:
    public function scopeVisiveis($query)
    {
        // Retorna a query padrão (que já exclui os deletados)
        return $query; 
    }

    public function scopeNaoRespondidas($query)
    {
        return $query->whereNull('respondida_em');
    }
}