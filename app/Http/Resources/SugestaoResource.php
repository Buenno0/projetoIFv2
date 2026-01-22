<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SugestaoResource extends JsonResource
{
    public function toArray($request)
    {
        // TRATAMENTO DE ENUM:
        // Se o status for um Objeto (Enum do PHP 8.1), pegamos o value string.
        // Se for string normal, usamos ela direto.
        $statusValue = $this->status;
        
        if (is_object($this->status)) {
            $statusValue = $this->status->value;
        }

        return [
            'id'       => $this->id,
            'conteudo' => $this->conteudo,
            'nome'     => $this->nome ?? 'Anônimo',
            'email'    => $this->email, // Útil caso queira mostrar no hover
            
            'status'   => $statusValue, 
            // --------------------------------------

            // Mantivemos data formatada para exibição direta
            'created_at'           => $this->created_at ? $this->created_at->toIso8601String() : null,
            'created_at_formatado' => optional($this->created_at)->format('d/m/Y H:i'),
            'tempo_decorrido' => $this->tempo_decorrido,

            // Dados da resposta
            'respondido_por' => $this->respondido_por,
            'data_resposta'  => optional($this->data_resposta)->format('d/m/Y H:i'),
            
            // Links para os botões funcionarem
            'links' => [
                'responder' => route('sugestoes.responder', $this->id),
                'destroy'   => route('sugestoes.destroy', $this->id),
            ],
        ];
    }
}