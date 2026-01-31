<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Sugestao extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = 'sugestoes';

    // Categorias pré-definidas para classificação pelo admin
    public const CATEGORIAS = [
        'infraestrutura' => 'Infraestrutura',
        'ensino' => 'Ensino',
        'biblioteca' => 'Biblioteca',
        'alimentacao' => 'Alimentação',
        'seguranca' => 'Segurança',
        'tecnologia' => 'Tecnologia',
        'acessibilidade' => 'Acessibilidade',
        'eventos' => 'Eventos e Cultura',
        'administrativo' => 'Administrativo',
        'esportes' => 'Esportes e Lazer',
        'outros' => 'Outros',
    ];

    protected $fillable = [
        'conteudo',
        'nome',
        'email',
        'status',
        'categoria',
        'data_resposta',
        'data_analise',
        'id_user_responded',
        'id_user_analysing',
        'respondido_por',
        'id_user_deleted',
        'modificado_por',
        'conteudo_resposta'
    ];

    protected $casts = [
        'data_resposta' => 'datetime',
        'data_analise'  => 'datetime',
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

    public function scopeVisiveis($query)
    {
        return $query;
    }

    public function scopeNaoRespondidas($query)
    {
        return $query->where('status', '!=', 'respondida');
    }

    public function usuarioQueAnalisou()
    {
        return $this->belongsTo(User::class, 'id_user_analysing');
    }

    public function usuarioQueRespondeu()
    {
        return $this->belongsTo(User::class, 'id_user_responded');
    }

    public function getTempoDecorridoAttribute()
    {
        // ... (verificações iniciais iguais) ...
        $statusAtual = is_object($this->status) ? $this->status->value : $this->status;

        if (!$this->data_analise || $statusAtual !== 'em_analise') {
            return null;
        }

        $inicio = Carbon::parse($this->data_analise);
        $agora = Carbon::now();
        
        $dias = $inicio->diffInDays($agora);
        $horas = $inicio->diffInHours($agora);

        // TEXTOS MAIS CURTOS E LIMPOS
        if ($horas < 24) {
            return '< 24h'; // Ou "Recente"
        }

        if ($dias == 1) {
            return '1 dia';
        }

        if ($dias < 7) {
            return "{$dias} dias";
        }

        if ($dias < 30) {
            $semanas = floor($dias / 7);
            return $semanas == 1 ? '1 sem' : "{$semanas} sem";
        }

        $meses = floor($dias / 30);
        return $meses == 1 ? '1 mês' : "{$meses} meses";
    }
}