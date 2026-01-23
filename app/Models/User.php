<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditoriaAvancada; 

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable;
    
    // 1. USE SIMPLES (Sem conflitos, pois mudamos o nome na Trait)
    use Auditable, AuditoriaAvancada;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
    protected $auditExclude = ['password', 'remember_token'];

    // 2. IMPLEMENTAÇÃO DA INTERFACE
    // Essa função é obrigatória pelo AuditableContract.
    // Aqui nós apenas repassamos o trabalho para a nossa Trait.
    public function transformAudit(array $data): array
    {
        return $this->gerarMetadadosAuditoria($data);
    }
}