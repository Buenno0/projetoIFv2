<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Sugestao extends Model implements AuditableContract
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'sugestoes';

    protected $fillable = [
        'nome',
        'email',
        'conteudo',
        'respondido',
        'data_resposta',
        'id_user_responded',
        'respondido_por',
        'id_user_deleted',
        'visible',
        'modificado_por',
    ];

    protected $casts = [
        'respondido'    => 'boolean',
        'visible'       => 'boolean',
        'data_resposta' => 'datetime',
        'deleted_at'    => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    protected static function booted()
{
    static::updating(function ($sugestao) {
        if ($sugestao->isDirty('visible') && $sugestao->visible === false) {
            $sugestao->id_user_deleted = auth()->id();
        }
    });
}

    public function scopeVisiveis(Builder $query): Builder
    {
        return $query->where('visible', true);
    }

    public function scopeNaoRespondidas($q)
    {
        return $q->where(function($q) {
            $q->where('respondido', false)
              ->orWhereNull('respondido');
        });
    }

    /**
     * Adiciona informações extras ao log de auditoria
     */
    public function getAuditMetadata(): array
    {
        return [
            'ip_address' => request()->ip(),
            'url'        => request()->fullUrl(),
            'user_agent' => request()->header('User-Agent'),
        ];
    }
}
