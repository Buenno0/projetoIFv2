<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SugestaoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'conteudo'        => $this->conteudo,
            'nome'            => $this->nome ?? 'Anônimo',
            'respondido'      => (bool) $this->respondido,
            'respondido_por'  => $this->respondido_por,
            'data_resposta'   => optional($this->data_resposta)->format('d/m/Y H:i'),
            'created_at'      => optional($this->created_at)->format('d/m/Y H:i'),
            // Links para ações (evita montar no front):
            'links' => [
                'responder' => route('sugestoes.responder', $this->id),
                'destroy'   => route('sugestoes.destroy', $this->id),
            ],
        ];
    }
}
