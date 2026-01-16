<?php

namespace App\Traits;

use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;

trait AuditoriaAvancada
{
    // MUDEI O NOME AQUI: de transformAudit para gerarMetadadosAuditoria
    public function gerarMetadadosAuditoria(array $data): array
    {
        $agent = new Agent();
        $agent->setUserAgent(Request::header('User-Agent'));
        
        $metadados = [
            'raw_agent' => Request::header('User-Agent'),
            'browser'   => $agent->browser() . ' ' . $agent->version($agent->browser()),
            'platform'  => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'device'    => $agent->device(),
            'is_mobile' => $agent->isMobile(),
            'method'    => Request::method(),
            'route'     => Request::route() ? Request::route()->getName() : 'console',
            'ip'        => Request::ip(),
            'session_id'=> session()->getId(),
        ];

        $data['user_agent'] = json_encode($metadados);

        return $data;
    }
}