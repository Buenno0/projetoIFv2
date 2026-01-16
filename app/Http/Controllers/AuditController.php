<?php
// app/Http/Controllers/AuditController.php
namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Response;

class AuditController extends Controller
{
    public function download()
    {
        $audits = Audit::with('user')->get();

        $data = $audits->map(function ($audit) {
            return [
                'id' => $audit->id,
                'event' => $audit->event,
                'user' => $audit->user ? $audit->user->name . ' (ID: ' . $audit->user->id . ')' : 'Não autenticado',
                'auditable_type' => $audit->auditable_type,
                'auditable_id' => $audit->auditable_id,
                'old_values' => $audit->old_values,
                'new_values' => $audit->new_values,
                'ip_address' => $audit->ip_address,
                'created_at' => $audit->created_at->toDateTimeString(),
            ];
        });

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileName = 'audits_' . now()->format('Y-m-d_H-i-s') . '.json';

        return Response::make($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=$fileName",
        ]);
    }
}
