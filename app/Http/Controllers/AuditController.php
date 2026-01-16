<?php

namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditController extends Controller
{
    /**
     * Exibe a listagem de auditorias na View
     */
    public function index()
    {
        // Carrega os logs com o usuário, ordenados pelo mais recente e paginados
        $audits = Audit::with('user')
            ->latest()
            ->paginate(20);

        // Certifique-se que o nome do arquivo em resources/views é auditorias/index.blade.php
        return view('auditorias.index', compact('audits'));
    }

    /**
     * Faz o download do JSON
     */
    public function download()
    {
        $audits = Audit::with('user')->latest()->get();

        $data = $audits->map(function ($audit) {
            return [
                'id' => $audit->id,
                'event' => strtoupper($audit->event),
                'user' => $audit->user ? $audit->user->name : 'Sistema',
                'model' => class_basename($audit->auditable_type),
                'old' => $audit->old_values,
                'new' => $audit->new_values,
                'ip' => $audit->ip_address,
                'date' => $audit->created_at->format('d/m/Y H:i:s'),
            ];
        });

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $fileName = 'auditoria_' . now()->format('Ymd_His') . '.json';

        return Response::make($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=$fileName",
        ]);
    }
}