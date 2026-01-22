<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SugestaoResource extends JsonResource
{
    public function toArray($request)
    {
        // Normaliza o status
        $statusValue = is_object($this->status) ? $this->status->value : $this->status;

        return [
            'id' => $this->id,
            'conteudo' => $this->conteudo,
            'nome' => $this->nome ?? 'Anônimo',
            'email' => $this->email,
            'status' => $statusValue,
            'tempo_decorrido' => $this->tempo_decorrido, // Seu accessor criado antes

            // DATAS
            'created_at' => optional($this->created_at)->toIso8601String(),
            'created_at_formatado' => optional($this->created_at)->format('d/m/Y H:i'),
            'data_resposta_formatada' => optional($this->data_resposta)->format('d/m/Y H:i'),

            // USUÁRIOS (Novos campos para a UI)
            'analista_nome' => $this->usuarioQueAnalisou->name ?? null,
            'respondente_nome' => $this->respondido_por ?? ($this->usuarioQueRespondeu->name ?? null),

            'links' => [
                'responder' => route('sugestoes.responder', $this->id),
                'destroy' => route('sugestoes.destroy', $this->id),
            ],
        ];
    }
}